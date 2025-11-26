<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController; // Nhớ dòng này

// 1. Trang chủ (Đặt tên là news.index)
Route::get('/', [NewsController::class, 'index'])->name('news.index'); 

// 2. Xem danh mục (Đặt tên là news.category)
Route::get('/danh-muc/{id}', [NewsController::class, 'category'])->name('news.category');

// 3. Xem chi tiết (Đặt tên là news.show)
Route::get('/bai-viet/{id}', [NewsController::class, 'show'])->name('news.show');

// 4. Gửi bình luận (Đặt tên là news.comment)
Route::post('/bai-viet/{id}/comment', [NewsController::class, 'comment'])->name('news.comment');