<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category; // <--- 1. NHỚ THÊM DÒNG NÀY ĐỂ GỌI MODEL CATEGORY
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        // Lấy tin tức (như cũ)
        $articles = Article::with(['source', 'categories'])
                          ->orderBy('id', 'desc')
                          ->paginate(10);
        
        // Lấy danh sách danh mục (MỚI)
        $categories = Category::all(); 

        // Truyền cả 2 biến sang View
        return view('news.index', compact('articles', 'categories'));
    }
    // Hàm lọc bài viết theo danh mục
    public function category($id)
    {
        // 1. Tìm danh mục theo ID (Nếu không thấy thì báo lỗi 404)
        $currentCategory = \App\Models\Category::findOrFail($id);
        
        // 2. Lấy các bài viết CHỈ thuộc danh mục này
        $articles = $currentCategory->articles()
                                    ->with(['source', 'categories']) // Lấy kèm nguồn và danh mục con
                                    ->orderBy('id', 'desc')          // Bài mới lên đầu
                                    ->paginate(10);                  // 10 bài/trang
        
        // 3. Vẫn lấy danh sách tất cả danh mục để hiển thị ở Sidebar
        $categories = \App\Models\Category::all();
        
        // 4. Trả về view giống trang chủ, nhưng có thêm biến $currentCategory
        return view('news.index', compact('articles', 'categories', 'currentCategory'));
    }
    // Hàm hiển thị chi tiết 1 bài báo
    public function show($id)
    {
        // Tìm bài viết theo ID, nếu không thấy thì báo lỗi 404
        $article = Article::with(['source', 'categories'])->findOrFail($id);
        
        // Trả về giao diện xem chi tiết
        return view('news.show', compact('article'));
    }
}