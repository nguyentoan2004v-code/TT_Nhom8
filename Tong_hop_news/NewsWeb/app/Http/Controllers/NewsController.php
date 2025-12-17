<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Source; 
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // 1. TRANG CHỦ 
    public function index(Request $request)
    {
        $query = Article::with(['source', 'categories'])
                        ->where('is_visible', true);

        // Tìm kiếm 
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('content', 'LIKE', "%{$keyword}%");
            });
            $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 ELSE 2 END", ["%{$keyword}%"]);
        }
        
        // Order by published_date if present, otherwise created_at — newest first
        $query->orderByRaw("COALESCE(published_date, created_at) DESC");
        $articles = $query->paginate(15)->appends($request->query());
        // Lấy dữ liệu cho Menu và Sidebar
        $categories = Category::all(); 
        $sources = Source::all(); 

        return view('news.index', compact('articles', 'categories', 'sources'));
    }

    // 2. LỌC TIN THEO DANH MỤC
    public function category($id)
    {
        $currentCategory = Category::findOrFail($id);
        $articles = $currentCategory->articles()
                        ->with(['source', 'categories'])
                                    ->where('is_visible', true)
                                    ->orderByRaw("COALESCE(published_date, created_at) DESC")
                        ->paginate(15);
                                    
        $categories = Category::all();
        $sources = Source::all(); 
        
        return view('news.index', compact('articles', 'categories', 'sources', 'currentCategory'));
    }

    // 3. LỌC TIN THEO NGUỒN BÁO
    public function source($id)
    {
        $currentSource = Source::findOrFail($id);
        $articles = $currentSource->articles()
                      ->with(['source', 'categories'])
                                  ->where('is_visible', true)
                                  ->orderByRaw("COALESCE(published_date, created_at) DESC")
                      ->paginate(15);
                                  
        $categories = Category::all();
        $sources = Source::all();
        
        return view('news.index', compact('articles', 'categories', 'sources', 'currentSource'));
    }

    // 4. XEM CHI TIẾT
    public function show($id)
    {
        $article = Article::with(['source', 'categories', 'comments' => function($q) {
            $q->whereNull('parent_id')->orderBy('id', 'desc');
        }])->where('id', $id)->where('is_visible', true)->firstOrFail();
        
        return view('news.show', compact('article'));
    }

    // 5. Comment, Like, Tim
    public function comment(Request $request, $id)
    {
        $request->validate(['name'=>'required', 'content'=>'required']);
        Comment::create([
            'article_id' => $id, 'name' => $request->name, 
            'content' => $request->content, 'parent_id' => $request->input('parent_id')
        ]);
        return redirect()->back()->with('success', 'Đã gửi bình luận!');
    }

    public function react($id, $type)
    {
        $comment = Comment::findOrFail($id);
        ($type == 'like') ? $comment->increment('likes') : $comment->increment('loves');
        return redirect()->back();
    }

    public function loveArticle($id)
    {
        Article::findOrFail($id)->increment('loves');
        return redirect()->back();
    }

    public function likeComment($id)
    {
        Comment::findOrFail($id)->increment('likes');
        return redirect()->back();
    }
}