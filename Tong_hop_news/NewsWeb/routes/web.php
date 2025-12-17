<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;



Route::get('/', [NewsController::class, 'index'])->name('news.index');

Route::get('/danh-muc/{id}', [NewsController::class, 'category'])->name('news.category');

Route::get('/nguon/{id}', [NewsController::class, 'source'])->name('news.source');

Route::get('/bai-viet/{id}', [NewsController::class, 'show'])->name('news.show');

Route::post('/bai-viet/{id}/comment', [NewsController::class, 'comment'])->name('news.comment');

Route::get('/article/{id}/love', [NewsController::class, 'loveArticle'])->name('article.love');

Route::get('/comment/{id}/like', [NewsController::class, 'likeComment'])->name('comment.like');

// --- Admin simple auth (UI button + dashboard) ---
use App\Http\Controllers\AdminController;

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Admin article management
Route::delete('/admin/article/{id}/delete', [AdminController::class, 'deleteArticle'])->name('admin.article.delete');
Route::post('/admin/article/{id}/toggle', [AdminController::class, 'toggleArticle'])->name('admin.article.toggle');
Route::post('/admin/fetch-news', [AdminController::class, 'fetchNews'])->name('admin.fetch-news');
Route::post('/admin/fetch-news-details', [AdminController::class, 'fetchNewsDetails'])->name('admin.fetch-news-details');
Route::get('/admin/scrape-status', [AdminController::class, 'getScrapeStatus'])->name('admin.scrape-status');