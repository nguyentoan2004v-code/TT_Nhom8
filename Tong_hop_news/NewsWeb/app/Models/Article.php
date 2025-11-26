<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    // Kết nối với Nguồn báo
    public function source() {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }
    // Kết nối với Danh mục
    public function categories() {
        return $this->belongsToMany(Category::class, 'article_category', 'article_id', 'category_id');
    }
}