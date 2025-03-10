<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('leave', [RedirectorPageController::class, '__invoke'])->name('leave.kodinger');
// Route::get('privacy-policy', function() {
// 	return view('privacy-policy');
// });

Auth::routes(['register' => false]);

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index')->middleware('permission:dashboard');
});

Route::group(['prefix' => 'communities', 'as' => 'community.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/create', [CommunityController::class, 'create'])->name('create')->middleware('permission:community-create');
        Route::get('/', [CommunityController::class, 'index'])->name('index')->middleware('permission:community-list');
        Route::get('/{id}/edit', [CommunityController::class, 'edit'])->name('edit')->middleware('permission:community-update');
        Route::put('/{id}/edit', [CommunityController::class, 'update'])->name('update')->middleware('permission:community-update');
        Route::patch('/{id}/edit', [CommunityController::class, 'update'])->name('update')->middleware('permission:community-update');
        Route::delete('/{id}/delete', [CommunityController::class, 'destroy'])->name('delete')->middleware('permission:community-delete');
        Route::post('/', [CommunityController::class, 'store'])->name('store')->middleware('permission:community-create');
    });
});

Route::group(['prefix' => 'posts', 'as' => 'post.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        // Route::get('/create', [PostController::class, 'create'])->name('create')->middleware('permission:post-create');
        Route::get('/', [PostController::class, 'index'])->name('index')->middleware('permission:post-list');
        Route::get('/{id}/publish', [PostController::class, 'publish'])->name('publish')->middleware('permission:post-publish');
        Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit')->middleware('permission:post-update');
        Route::put('/{id}/edit', [PostController::class, 'update'])->name('update')->middleware('permission:post-update');
        Route::patch('/{id}/edit', [PostController::class, 'update'])->name('update')->middleware('permission:post-update');
        Route::delete('/{id}/delete', [PostController::class, 'destroy'])->name('delete')->middleware('permission:post-delete');
        Route::post('/', [PostController::class, 'store'])->name('store')->middleware('permission:post-create');
        Route::post('/discover', [PostController::class, 'storeDiscover'])->name('store_discover');
    });
});

Route::group(['prefix' => 'contributes', 'as' => 'contribute.', 'middleware' => 'auth'], function () {
    Route::get('/', [ContributeController::class, 'index'])->name('index');
    Route::get('/{slug}', [ContributeController::class, 'create'])->name('create');
    Route::delete('{id}', [ContributeController::class, 'destroy'])->name('delete');
    Route::get('{id}/merge', [ContributeController::class, 'merge'])->name('merge');
    Route::post('{id}/reject', [ContributeController::class, 'reject'])->name('reject');
});

Route::group(['prefix' => 'manage-users', 'as' => 'user.'], function () {
    // Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:user-list');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:user-update');
    Route::put('/{id}/edit', [UserController::class, 'update'])->name('update')->middleware('permission:user-update');
    Route::patch('/{id}/edit', [UserController::class, 'update'])->name('update')->middleware('permission:user-update');
    Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('delete')->middleware('permission:user-delete');
    // Route::post('/', [UserController::class, 'store'])->name('store');
});

Route::group(['prefix' => 'comments', 'as' => 'comment.'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::delete('/{id}/delete', [CommentController::class, 'destroy'])->name('delete');
    });
});

Route::get('auth/{provider}', [Auth\AuthController::class, 'redirectToProvider'])->name('auth');
Route::get('auth/{provider}/callback', [Auth\AuthController::class, 'handleProviderCallback']);

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/post-slide/{id?}', [FrontendController::class, 'postSlide'])->name('post.slide');
Route::get('/post-md/{id?}', [FrontendController::class, 'postMD'])->name('post.md');
Route::get('/post-link', [FrontendController::class, 'postLink'])->name('post.link');
Route::get('/post', [FrontendController::class, 'newPost'])->name('post.new');
Route::get('/users', [FrontendController::class, 'users'])->name('users');
Route::get('/community', [FrontendController::class, 'community'])->name('community');
Route::get('/scenes', [FrontendController::class, 'scenes'])->name('scenes');
Route::get('/scene', [FrontendController::class, 'scene'])->name('scene');
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
// Route::get('/discover/{tag?}', [FrontendController::class, 'discover'])->name('discover');
Route::get('/{slug}/loves', [FrontendController::class, 'profileLoves'])->name('loves');
Route::get('/notifications', [FrontendController::class, 'profileNotifications'])->name('notifications');
Route::get('/saves', [FrontendController::class, 'profileSaves'])->name('saves');
Route::get('/contribute/myposts', [FrontendController::class, 'contributesMyPosts'])->name('contribute.myposts');
Route::get('/{slug?}/contributes', [FrontendController::class, 'contributes'])->name('contributes');
Route::get('/{slug?}/delete', [FrontendController::class, 'deletePost'])->name('deletePost');
Route::get('/{slug}/discuss', [FrontendController::class, 'discuss'])->name('discuss');
Route::get('/setting', [FrontendController::class, 'setting'])->name('setting');
Route::post('/setting', [FrontendController::class, 'settingUpdate'])->name('setting_update');
Route::get('/{slug?}', [FrontendController::class, 'single'])->name('single');
Route::get('/{username?}/{slug?}', [FrontendController::class, 'singlePost'])->name('post.show');
Route::get('/tag/{slug}', [FrontendController::class, 'index'])->name('tag');
// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('_/notification/{payload}', [FrontendController::class, 'openNotification'])->name('notification.open');
