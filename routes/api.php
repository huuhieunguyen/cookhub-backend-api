<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Controllers\AuthenController;
use App\Http\Controllers\Authen\ChangePasswordController;
// use App\Http\Controllers\Authen\ForgotPasswordController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\FollowController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Broadcast::routes(['middleware' => ['auth:sanctum']]);
// Broadcast::routes(["prefix" => "api", "middleware" => ['auth:sanctum']]);

Route::prefix('v1/authen')->group(function () {
    Route::post('/register', [AuthenController::class, 'register']);
    Route::post('/login', [AuthenController::class, 'login']);

    Route::post('/forgot-password', [App\Http\Controllers\Authentication\ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/verify/pin', [App\Http\Controllers\Authentication\ForgotPasswordController::class, 'verifyPin']);
    Route::post('/reset-password', [App\Http\Controllers\Authentication\ResetPasswordController::class, 'resetPassword']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('v1/authen')->group(function () {
        Route::get('/me', [AuthenController::class, 'me']);
        Route::post('/logout', [AuthenController::class, 'logout']);
        Route::patch('/user', [UserController::class, 'update']);
        Route::patch('/change-password', [ChangePasswordController::class, 'changePassword']);
    });

    Route::prefix('v1/posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/{postId}', [PostController::class, 'update']);
        Route::delete('/{postId}', [PostController::class, 'destroy']);

        Route::post('/like', [PostLikeController::class, 'store']);
        Route::delete('/{postId}/unlike', [PostLikeController::class, 'destroy']);

        Route::get('/get-post-by-id/{postId}', [PostController::class, 'getPostById']);
    });

    Route::prefix('v1/recipes')->group(function () {
        Route::post('/add-new-recipe', [RecipeController::class, 'addNewRecipe']);
        Route::get('/get-my-recipes', [RecipeController::class, 'getMyRecipes']);
        Route::patch('/update-recipe/{recipeId}', [RecipeController::class, 'updateRecipe']);
        Route::delete('/delete-recipe/{recipeId}', [RecipeController::class, 'deleteRecipe']);
    });

    Route::prefix('v1/user-relationships')->group(function () {
        Route::get('/followers', [FollowController::class, 'getFollowers']);
        Route::get('/followees', [FollowController::class, 'getFollowees']);
        Route::get('/{userId}', [FollowController::class, 'getRelationshipStatus']);

        Route::post('/follow', [FollowController::class, 'follow']);
        Route::delete('/unfollow', [FollowController::class, 'unfollow']);
    });

    // Chat routes
    Route::prefix('v1/chat')->group(function () {
        Route::post('/create-chat',[ChatController::class, 'createChat']);
        Route::get('/get-chats',[ChatController::class, 'getChats']);
        Route::get('/search-user',[ChatController::class, 'searchUsers']);
        Route::post('/send-text-message',[ChatController::class, 'sendTextMessage']);
        Route::patch('/message-status/{message}',[ChatController::class, 'messageStatus']);
        Route::get('/get-messages-by-id/{chat}',[ChatController::class, 'getMessagesById']);
    });

    Route::prefix('v1/comments')->group(function () {
        Route::post('/create-comment', [CommentController::class, 'createComment']);
        Route::delete('/delete-comment/{commentId}', [CommentController::class, 'deleteCommentById']);
        Route::patch('/update-comment/{commentId}', [CommentController::class, 'updateCommentById']);
    });

    Route::prefix('v1/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::patch('/read-notification/{notificationId}', [NotificationController::class, 'markAsRead']);
    });

});

// Users
Route::prefix('v1/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::delete('/{id}', [UserController::class, 'destroy']);

});

// Recipes
Route::prefix('v1/recipes')->group(function () {
    Route::get('/get-all-recipes', [RecipeController::class, 'getAllRecipes']);
    Route::get('/get-recipe/{recipeId}', [RecipeController::class, 'getRecipeById']);
    Route::get('/get-recipes-by-author/{authorId}', [RecipeController::class, 'getRecipesByAuthorId']);
    Route::delete('/delete-recipes-by-admin/{recipeId}', [RecipeController::class, 'deleteRecipeByAdmin']);
});

// Comments
Route::prefix('v1/comments')->group(function () {
    Route::get('/get-comment/{commentId}', [CommentController::class, 'getCommentById']);
    Route::get('/get-comments/{recipeId}', [CommentController::class, 'getCommentsByRecipe']);
});

// Posts
Route::prefix('v1/posts')->group(function () {
    Route::get('/', [PostController::class, 'getPostsWithLikes']);
    Route::get('/posts_without_users_like', [PostController::class, 'getPosts']);
    Route::get('/{authorId}', [PostController::class, 'getPostsByAuthorIdWithLikes']);
    Route::get('/by_author_without_users_like/{authorId}', [PostController::class, 'getPostsByAuthorID']);
});

/*
    Just a test
*/
Route::get('/hello_world/', function (Request $request) {
    return ['msg' => 'hello_world'];
});

/*
    Laravel check on the Postgres connection
*/
Route::get('/test_postgres/', function (Request $request) {
    try {
        DB::connection()->getPdo();
        return ['status' => 'executed', 'data' => 'Successfully connected to the DB.' ];
    } catch (\Exception $e) {
        return ['status' => 'FAIL. exception', 'data' => $e ];
    }
});
