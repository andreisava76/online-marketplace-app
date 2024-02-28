<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Listing;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ListingController extends Controller
{
    public function index(): Factory|View|Application
    {
        $categories = Category::query()->get();
        $listings = Listing::query()->with(['images'])->latest()->filter(
            request(['search', 'category'])
        )->paginate(6);

        return view('components.index',
            compact('listings', 'categories')
        );
    }

    public function show(Listing $listing): Factory|View|Application
    {
        $images = Image::query()->where('listing_id', $listing->id)->get();
        $comments = Comment::query()->where('listing_id', $listing->id)->latest()->get();

        return view('components.listing.show-listing',
            compact('images', 'comments'),
            ['listing' => $listing]
        );
    }

    public function edit(Listing $listing): Factory|View|Application|RedirectResponse
    {
        if ($listing->user_id == \auth()->id() || Auth::user()->can('admin')) {
            $images = Image::query()->where('listing_id', $listing->id)->get();
            return view('components.listing.edit-listing',
                compact('images'),
                ['listing' => $listing]
            );
        }
        return back()->with('error', 'Nu aveti permisiunea sa editati anuntul');
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        $categories = Category::all();
        return view('components.listing.create-listing',
            compact('categories'));
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Request $request): Redirector|RedirectResponse|Application
    {
        $attributes = $this->validate($request, ['title' => ['required', 'min:3', 'max:100'],
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'description' => ['required', 'min:10'],
                'condition' => ['required', Rule::in(['new', 'used'])],
                'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'max:15'],
                'email' => ['required', 'email'],
                'phone' => ['required', 'regex:^(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-)?([0-9]{3}(\s|\.|\-|)){2}$^']]
        );

        if (!isset($request->price_right)) {
            $request['price_right'] = '00';
        }

        $request->merge([
            'price' => $request->price_left . '.' . $request->price_right
        ]);

        $listing_id = Listing::create(array_merge($attributes, [
            'user_id' => $request->user()->id
        ]))->getKey();

        $files = $request->file("image_upload");
        if ($request->hasFile("image_upload")) {
            if (count($request->image_upload) > 6) {
                return redirect('/listings/create')
                    ->with('error', 'Maxim 6 fisiere permise');
            }
            foreach ($files as $file) {
                $rules = ['file' => 'required', 'file', 'mimes:jpg,jpeg,png',
                    Rule::exists('listings', 'id'),
                    Rule::exists('users', 'id')
                ];
                $validator = Validator::make(array('file' => $file), $rules);
                if ($validator->passes()) {
                    $destinationPath = storage_path("app/public/files");
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    Image::create(['image' => $filename,
                        'user_id' => $request->user()->id,
                        'listing_id' => $listing_id
                    ]);
                    $file->move($destinationPath, $filename);
                }
            }
        }

        return redirect('/')->with('success', 'Anuntul a fost adaugat');
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Listing $listing): Redirector|Application|RedirectResponse
    {
        $attributes = $this->validate($request, ['title' => ['required', 'min:3', 'max:100'],
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'description' => ['required', 'min:10'],
                'condition' => ['required', Rule::in(['new', 'used'])],
                'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'max:15'],
                'email' => ['required', 'email'],
                'phone' => ['required', 'regex:^(\+4|)?(07[0-8]{1}[0-9]{1}|02[0-9]{2}|03[0-9]{2}){1}?(\s|\.|\-)?([0-9]{3}(\s|\.|\-|)){2}$^']]
        );

        if (!isset($request->price_right)) {
            $request['price_right'] = '00';
        }

        $request->merge([
            'price' => $request->price_left . '.' . $request->price_right
        ]);

        $files = $request->file("image_upload");
        if ($request->hasFile("image_upload")) {
            $listing->images()->delete();
            if (count($request->image_upload) > 6) {
                return redirect('/listings/create')
                    ->with('error', 'Maxim 6 fisiere permise');
            }
            foreach ($files as $file) {
                $rules = ['file' => 'required', 'file', 'mimes:jpg,jpeg,png',
                    Rule::exists('listings', 'id'),
                    Rule::exists('users', 'id')
                ];
                $validator = Validator::make(array('file' => $file), $rules);
                if ($validator->passes()) {
                    $destinationPath = storage_path("app/public/files");
                    $filename = time() . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                    Image::create(['image' => $filename,
                        'user_id' => $request->user()->id,
                        'listing_id' => $listing->id
                    ]);
                    $file->move($destinationPath, $filename);
                }
            }
        }
        (new SlugService())->slug($listing, true);
        $listing->update($attributes);
        return redirect('/')->with('success', 'Anuntul a fost editat');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        if ($listing->delete()) {
            return back()->with('success', 'Anuntul a fost sters');
        }

        return back()->withErrors(['msg' => 'Anuntul nu a putut fi sters']);
    }

    public function show_user_listings(): Factory|View|Application
    {
        return view('components.listing.user-listings', [
            'listings' => Listing::query()->with(['images',], ['user_id'])
                ->whereUserId(auth()->id())->latest()
                ->paginate(6)
        ]);
    }
}
