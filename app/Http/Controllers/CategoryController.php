<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class CategoryController extends Controller
{
    public function create(): Factory|View|Application
    {
        return view('components.category.create-category');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => ['required', 'max:50', 'unique:categories,name'],
        ]);

        Category::query()->create(['name' => $request->get('name')]);
        return back()->with('success', 'Categoria a fost adaugata');
    }

}
