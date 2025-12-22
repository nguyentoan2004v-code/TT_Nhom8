<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // Hiển thị form login đơn giản
    public function showLogin()
    {
        return view('admin.login');
    }

    // Xử lý đăng nhập (lấy dữ liệu từ database)
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Tìm user trong database
        $user = DB::table('users')
                    ->where('email', $request->username)
                    ->first();

        // Kiểm tra user tồn tại và mật khẩu đúng
        if ($user && $user->password === $request->password) {
            Session::put('is_admin', true);
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Email hoặc mật khẩu không đúng'])->withInput();
    }

    // Dashboard admin - Hiển thị danh sách tin tức
    public function dashboard(\Illuminate\Http\Request $request)
    {
        if (!Session::get('is_admin')) {
            return redirect()->route('admin.login');
        }

        // Lấy danh sách tin tức, hỗ trợ lọc theo nguồn (source_id)
        $query = DB::table('articles')
                ->join('sources', 'articles.source_id', '=', 'sources.id')
                ->select('articles.*', 'sources.name as source_name');

        if ($request->has('source_id') && $request->source_id) {
            $query->where('articles.source_id', $request->source_id);
        }

        $articles = $query->orderByRaw("COALESCE(articles.published_date, articles.created_at) DESC")
                          ->paginate(12);

        // Lấy danh sách category và nguồn
        $categories = DB::table('categories')->get();
        $sources = DB::table('sources')->get();

        // Lấy mapping article -> categories
        $articleIds = $articles->pluck('id')->toArray();
        $categoryRows = DB::table('article_category')
                            ->join('categories', 'article_category.category_id', '=', 'categories.id')
                            ->whereIn('article_category.article_id', $articleIds)
                            ->select('article_category.article_id', 'categories.name')
                            ->get();

        $categoryMap = [];
        foreach ($categoryRows as $row) {
            $categoryMap[$row->article_id][] = $row->name;
        }

        return view('admin.dashboard', [
            'articles' => $articles,
            'categories' => $categories,
            'sources' => $sources,
            'categoryMap' => $categoryMap,
            'selectedSource' => $request->source_id ?? null,
        ]);
    }

    // Xóa bài báo
    public function deleteArticle($id)
    {
        if (!Session::get('is_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Xóa từ article_category trước
            DB::table('article_category')->where('article_id', $id)->delete();
            // Xóa bài báo
            DB::table('articles')->where('id', $id)->delete();
            
            return response()->json(['success' => true, 'message' => 'Bài báo đã được xóa']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Ẩn/Hiện bài báo
    public function toggleArticle($id)
    {
        if (!Session::get('is_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Ensure the column exists (helpful if migration not yet run)
            if (!Schema::hasColumn('articles', 'is_visible')) {
                Schema::table('articles', function ($table) {
                    $table->boolean('is_visible')->default(true)->after('image_url');
                });
            }

            $article = DB::table('articles')->find($id);
            if (!$article) {
                return response()->json(['error' => 'Bài báo không tồn tại'], 404);
            }

            // Toggle is_visible (tạo cột nếu chưa có)
            $current = $article->is_visible ?? true;
            $new = $current ? 0 : 1;
            DB::table('articles')->where('id', $id)->update(['is_visible' => $new]);

            return response()->json([
                'success' => true,
                'message' => $new ? 'Đã hiện bài' : 'Đã ẩn bài',
                'is_visible' => (bool)$new
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Lấy tin mới từ scraper - Hiện cửa sổ CMD để theo dõi
    public function fetchNews()
    {
        if (!Session::get('is_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Sử dụng base_path để lấy đường dẫn tương đối từ gốc project Laravel
            // Giả sử project đặt tại: .../Tong_hop_news/NewsWeb
            // Thư mục scraper là: .../Tong_hop_news (là thư mục cha của NewsWeb)
            $scraperDir = realpath(base_path('..')); 
            $batFile = $scraperDir . DIRECTORY_SEPARATOR . 'run_auto.bat';
            $pyScript = $scraperDir . DIRECTORY_SEPARATOR . 'main.py';

            // Ghi log trigger (sử dụng đường dẫn linh hoạt)
            try {
                $triggerFile = $scraperDir . DIRECTORY_SEPARATOR . 'fetch_trigger.log';
                file_put_contents($triggerFile, "[" . date('Y-m-d H:i:s') . "] fetchNews called\n", FILE_APPEND | LOCK_EX);
            } catch (\Throwable $e) {}

            if (PHP_OS_FAMILY === 'Windows') {
                // Chạy file .bat ở chế độ ẩn (/b)
                $batEsc = escapeshellarg($batFile);
                $command = "start \"Scraper\" /b cmd /c $batEsc";
                exec($command);
            } else {
                // Cho Linux/Mac
                $pyEsc = escapeshellarg($pyScript);
                $dirEsc = escapeshellarg($scraperDir);
                $command = "cd $dirEsc && python3 main.py > /dev/null 2>&1 &";
                exec($command);
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Đang chạy scraper (ẩn). Dùng "Chi tiết" để theo dõi.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Mở cửa sổ hiển thị tiến trình (Chỉ hoạt động khi chạy localhost trên máy tính cá nhân)
    public function fetchNewsDetails()
    {
        if (!Session::get('is_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $scraperDir = realpath(base_path('..'));
            $batFile = $scraperDir . DIRECTORY_SEPARATOR . 'run_auto.bat';

            if (PHP_OS_FAMILY === 'Windows') {
                $batEsc = escapeshellarg($batFile);
                // Lệnh này sẽ mở một cửa sổ CMD mới (chỉ thấy được nếu bạn đang dùng localhost)
                $command = "start \"Scraper Progress\" cmd /k $batEsc";
                exec($command);
            } else {
                // Linux thường không hỗ trợ mở cửa sổ GUI từ web server trực tiếp
                $dirEsc = escapeshellarg($scraperDir);
                $command = "cd $dirEsc && python3 main.py &";
                exec($command);
            }

            return response()->json(['success' => true, 'message' => 'Mở cửa sổ hiển thị tiến trình.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Endpoint to read scrape_status.json so frontend can poll for completion
    public function getScrapeStatus()
    {
        if (!Session::get('is_admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $statusFile = base_path('Tong_hop_news/scrape_status.json');
        if (!file_exists($statusFile)) {
            return response()->json(['status' => 'idle', 'message' => 'No status file']);
        }

        try {
            $content = file_get_contents($statusFile);
            $json = json_decode($content, true) ?: ['status' => 'unknown', 'message' => 'Invalid JSON'];
            return response()->json($json);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Logout
    public function logout()
    {
        Session::forget('is_admin');
        Session::forget('user_id');
        Session::forget('user_name');
        return redirect()->route('news.index');
    }
}
