<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['article_id', 'name', 'content', 'likes', 'parent_id']; // Thêm parent_id

    // Quan hệ: Một comment có thể có nhiều câu trả lời (children)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}