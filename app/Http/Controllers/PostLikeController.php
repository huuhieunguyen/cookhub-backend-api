<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Post;
use App\Models\Notification;
use Pusher\Pusher;
use App\Handlers\NotificationHandler;
use App\Events\PostLiked;

class PostLikeController extends Controller
{
    public function store(Request $request, $postId)
    {
        $user = Auth::user();

        try {
            $post = Post::findOrFail($postId);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Check if the user has already liked the post
        $existingLike = Like::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->first();
        
        if ($existingLike) {
            return response()->json(['message' => 'You have already liked the post'], 400);
        }
        
        // Create the like
        $like = new Like();
        $like->user_id = $user->id;
        $like->post_id = $post->id;
        $like->save();
        
        // Update the like_count field in the posts table
        $post->increment('like_count');

        event(new PostLiked($like));

        // Create the notification
        $notification = new Notification();
        $notification->user_id = $post->author_id;
        $notification->message = "{$user->name} liked your post {$post->id}.";
        $notification->save();
        
        $post->load(['author:id,name,avatar_url,is_active,last_active_time']);
        $post->load('images');
        return response()->json([
            'message' => 'Post liked successfully',
            'user_like' => $user,
            'post' => $post
        ], 201);
    }

    // protected $notificationHandler;

    // public function __construct(NotificationHandler $notificationHandler)
    // {
    //     $this->notificationHandler = $notificationHandler;
    // }

    // public function store(Request $request, $postId)
    // {
    //     // ...

    //     $like->save();

    //     $notificationMessage = "{$user->name} liked your post {$post->id}.";
    //     $this->notificationHandler->sendNotification('post-like-channel', 'post-like-event', $post->author_id, $notificationMessage);

    //     // ...
    // }

    // have store noti in DB
    // public function store(Request $request, $postId)
    // {
    //     // ...

    //     $like->save();

    //     $notificationMessage = "{$user->name} liked your post {$post->id}.";
    //     $this->notificationHandler->storeNotification($post->author_id, $notificationMessage);

    //     // ...
    // }
    public function destroy($postId)
    {
        $user = Auth::user();

        try {
            $post = Post::findOrFail($postId);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $like = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();
        if (!$like) {
            return response()->json(['message' => 'User has not liked the post'], 404);
        }

        $like->delete();

        // Update the like_count field in the posts table
        $post->decrement('like_count');

        return response()->json([
            'message' => 'Post unliked successfully',
            'post_id' => $post->id
        ]);
    }
}
