<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function index(Listing $listing): JsonResponse
    {
        return response()->json($listing->comments()->with('user')->latest()->get());
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, Listing $listing): string
    {
        $this->validate($request, [
                'body' => ['required', 'min:1'],
                'user_id' => ['required']
            ]
        );
        $comment = $listing->comments()->create([
            'body' => $request->get('body'),
            'user_id' => $request->get('user_id'),
            'listing_id' => $listing->id
        ]);

        broadcast(new NewComment($comment))->toOthers();
        return $comment->toJson();
    }
}
