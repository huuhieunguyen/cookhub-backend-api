<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentWithoutRecipeResource;
use App\Events\CommentSent;
use App\Models\Comment;
use App\Models\Recipe;

class CommentController extends Controller
{
    public function createComment(Request $request)
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        // Validate the request data
        $validatedData = $request->validate([
            'content' => 'required',
            'recipe_id' => 'required|exists:recipes,id',
            'rating' => 'nullable|integer',
        ]);

        // Create a new comment
        $comment = Comment::create([
            'user_id' => $user->id,
            'recipe_id' => $validatedData['recipe_id'],
            'content' => $validatedData['content'],
            'rating' => $validatedData['rating'],
        ]);

        // calculate avg rating for the recipe & update review_count
        $recipe = Recipe::find($request->recipe_id); // Get a specific recipe
        $recipe->calculateAverageRating();
        $recipe->reviews_count();

        $recipe->save();

        // broadcast(new CommentSent($comment));
        event(new CommentSent($comment));

        return response()->json([
            'message' => 'Comment sent successfully',
            'comment' => new CommentResource($comment),
        ],200);
    }

    public function getCommentById($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json([
            'comment' => new CommentResource($comment),
        ],200);
    }

    public function getCommentsByRecipe(Request $request, $recipeId)
    {
        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $perPage = $request->query('perPage', 10);
        $comments = $recipe->comments()
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        return response()->json([
            'recipe_id' => $recipe->id,
            // 'author' => $recipe->author,
            'comments' => CommentWithoutRecipeResource::collection($comments),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ]
        ],200);
    }

    public function updateCommentById(Request $request, $commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Check if the authenticated user is the owner of the comment
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment->content = $request->input('content');
        $comment->rating = $request->input('rating');

        // calculate avg rating for the recipe & update review_count
        $recipe = Recipe::find($comment->recipe_id); // Get a specific recipe
        $recipe->calculateAverageRating();
        $recipe->save();

        $comment->save();

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }

    public function deleteCommentById($commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        // Check if the authenticated user is the author of the recipe or the owner of the comment
        if (Auth::id() !== $comment->recipe->user_id && Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment->delete();

        // calculate avg rating for the recipe & update review_count
        $recipe = Recipe::find($comment->recipe_id); // Get a specific recipe
        $recipe->calculateAverageRating();
        $recipe->reviews_count();

        $recipe->save();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
