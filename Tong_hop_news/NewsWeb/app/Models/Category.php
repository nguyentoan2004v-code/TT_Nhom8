<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories'; // Tên bảng trong database
    
    // --- ĐÂY LÀ HÀM BẠN ĐANG THIẾU ---
    // Hàm này báo cho Laravel biết: 1 Danh mục có nhiều Bài viết
    public function articles()
    {
        // Quan hệ nhiều-nhiều thông qua bảng trung gian 'article_category'
        return $this->belongsToMany(Article::class, 'article_category', 'category_id', 'article_id');
    }
}