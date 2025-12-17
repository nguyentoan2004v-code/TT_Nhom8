<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý Admin - Báo Đốm</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --primary: #ff6b00; --secondary: #2c3e50; --bg-gray: #f7f7f7; }
        
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: var(--bg-gray); 
            color: #333; 
            padding-top: 80px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar { background: #fff; border-bottom: 3px solid var(--primary); height: 70px; padding: 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header-container { display: flex; align-items: center; justify-content: space-between; flex-wrap: nowrap; width: 100%; padding: 0 20px; }
        
        .brand-logo { font-family: 'Merriweather', serif; font-weight: 900; font-size: 1.6rem; color: var(--primary); text-decoration: none; margin-right: 30px; white-space: nowrap; display: flex; align-items: center; flex: 0 0 auto; }
        
        .admin-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 0 0 auto;
        }

        .admin-greeting {
            color: var(--secondary);
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-greeting i {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .btn-fetch {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-fetch:hover {
            background: #e55a00;
            box-shadow: 0 4px 12px rgba(255, 107, 0, 0.3);
        }

        .btn-fetch.loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .btn-logout {
            background: transparent;
            color: var(--secondary);
            border: 1px solid var(--secondary);
            border-radius: 6px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: var(--secondary);
            color: #fff;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px 20px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .admin-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-title i {
            color: var(--primary);
        }

        .section-title { 
            font-weight: 800; 
            color: #222; 
            border-left: 5px solid var(--primary); 
            padding-left: 15px; 
            margin: 20px 0 25px; 
            text-transform: uppercase; 
            font-size: 1.1rem; 
        }

        /* News Grid */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .news-card { 
            background: #fff; 
            border: none; 
            border-radius: 10px; 
            overflow: hidden; 
            height: 100%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.3s ease;
        }

        .news-card:hover { 
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-img-wrap { 
            position: relative; 
            padding-top: 60%; 
            overflow: hidden; 
            background: #eee; 
        }

        .card-img-top { 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }

        .placeholder-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #bdc3c7, #2c3e50);
            color: rgba(255,255,255,0.8);
            font-size: 3rem;
        }

        .source-tag { 
            position: absolute; 
            top: 10px; 
            right: 10px; 
            background: rgba(0,0,0,0.7); 
            color: #fff; 
            padding: 4px 10px; 
            font-size: 0.75rem; 
            border-radius: 4px; 
            font-weight: bold; 
            z-index: 5; 
        }

        .card-body { 
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .news-title { 
            font-size: 0.95rem; 
            font-weight: 700; 
            line-height: 1.4; 
            margin-bottom: 10px; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden; 
            color: #333;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }

        .btn-action {
            flex: 1;
            padding: 8px 10px;
            border: none;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            text-decoration: none;
        }

        .btn-delete {
            background: #ff4444;
            color: #fff;
        }

        .btn-delete:hover {
            background: #cc0000;
        }

        .btn-hide {
            background: #ffa500;
            color: #fff;
        }

        .btn-hide:hover {
            background: #ff8c00;
        }

        .btn-view {
            background: var(--primary);
            color: #fff;
        }

        .btn-view:hover {
            background: #e55a00;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 40px;
        }

        .page-link {
            color: var(--primary);
            border: 1px solid #ddd;
        }

        .page-link:hover {
            background-color: #f5f5f5;
            color: var(--primary);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Alert */
        .alert-box {
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            z-index: 9999;
            max-width: 300px;
        }

        .alert-box.success {
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-box.error {
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert-box.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(350px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Footer */
        .main-footer {
            background-color: #1a1a1a;
            color: #b0b0b0;
            padding-top: 60px;
            margin-top: 80px;
            font-size: 0.95rem;
        }

        .footer-brand {
            color: var(--primary);
            font-family: 'Merriweather', serif;
            font-size: 1.8rem;
            font-weight: 900;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }

        .copyright {
            background: #111;
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
            font-size: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>

    <nav class="navbar fixed-top">
        <div class="header-container">
            <a href="{{ url('/') }}" class="brand-logo"><i class="fas fa-paw"></i>BÁO ĐỐM</a>
            
            <div class="admin-controls">
                <div class="btn-group me-2" role="group" aria-label="Cập nhật">
                    <button class="btn-fetch btn btn-sm btn-primary" id="fetchBtn" onclick="fetchNews()">
                        <i class="fas fa-sync-alt"></i> Cập nhật (ẩn)
                    </button>
                    <button class="btn-fetch-details btn btn-sm btn-outline-light" id="fetchDetailsBtn" onclick="fetchNewsDetails()">
                        <i class="fas fa-desktop"></i> Chi tiết
                    </button>
                </div>

                <div class="admin-greeting">
                    <i class="fas fa-user-tie"></i>
                    <span>Xin chào, {{ Session::get('user_name') }}</span>
                </div>

                <a href="{{ route('admin.logout') }}" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container-fluid">
            @php
                $icons = [
                    'Chính trị - Xã hội' => 'fa-landmark', 'Kinh doanh' => 'fa-chart-line', 'Giáo dục' => 'fa-graduation-cap',
                    'Thể thao' => 'fa-futbol', 'Pháp luật' => 'fa-gavel', 'Giải trí' => 'fa-music',
                    'Công nghệ' => 'fa-microchip', 'Sức khỏe' => 'fa-heartbeat', 'Đời sống' => 'fa-coffee',
                ];
                $fallbackColors = [
                    'Thể thao' => 'bg-the-thao', 'Pháp luật' => 'bg-phap-luat', 'Kinh doanh' => 'bg-cong-nghe'
                ];
            @endphp
            <div class="admin-header">
                <div class="admin-title">
                    <i class="fas fa-cogs"></i>
                    Quản lý Bài báo
                </div>
                <div style="font-size: 0.9rem; color: #888;">
                    Tổng: <strong>{{ $articles->total() }}</strong> bài
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h2 class="section-title mb-0">Danh sách tin tức</h2>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                    <select name="source_id" class="form-select form-select-sm me-2" onchange="this.form.submit()" style="min-width:180px;">
                        <option value="">Tất cả nguồn</option>
                        @if(isset($sources))
                            @foreach($sources as $s)
                                <option value="{{ $s->id }}" {{ (isset($selectedSource) && $selectedSource == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @if(isset($selectedSource) && $selectedSource)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center" title="Bỏ lọc nguồn">
                            <i class="fas fa-times me-1"></i>
                            Bỏ lọc
                        </a>
                    @endif
                </form>
            </div>

            @if($articles->count() > 0)
                <div class="news-grid">
                    @foreach($articles as $article)
                        <div class="news-card">
                            <div class="card-img-wrap">
                                @php
                                    $firstCat = isset($categoryMap[$article->id]) ? ($categoryMap[$article->id][0] ?? null) : null;
                                    $iconClass = $icons[$firstCat] ?? 'fa-newspaper';
                                    $bgClass = $fallbackColors[$firstCat] ?? 'bg-default';
                                @endphp

                                @if($article->image_url)
                                    <img src="{{ $article->image_url }}" class="card-img-top" alt="{{ $article->title }}"
                                         onerror="this.style.display='none'; document.getElementById('placeholder-{{ $article->id }}').style.display='flex';">
                                    <div id="placeholder-{{ $article->id }}" class="placeholder-img {{ $bgClass }}" style="display:none;">
                                        <i class="fas {{ $iconClass }} fa-3x"></i>
                                    </div>
                                @else
                                    <div class="placeholder-img {{ $bgClass }}">
                                        <i class="fas {{ $iconClass }} fa-3x"></i>
                                    </div>
                                @endif
                                <span class="source-tag">{{ $article->source_name }}</span>
                            </div>

                            <div class="card-body">
                                <h5 class="news-title">{{ $article->title }}</h5>

                                <div style="font-size:0.85rem; color:#666; margin-bottom:8px;">
                                    <strong>Trạng thái:</strong> <span id="status-{{ $article->id }}">{{ $article->is_visible ?? true ? 'Đang hiển thị' : 'Đang ẩn' }}</span>
                                </div>

                                @if(isset($categoryMap[$article->id]))
                                    <div style="margin-bottom:8px;">
                                        @foreach($categoryMap[$article->id] as $c)
                                            <span class="badge bg-light text-dark" style="font-size:0.75rem; margin-right:4px;">{{ $c }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="card-actions">
                                    <a href="{{ route('news.show', ['id' => $article->id]) }}" class="btn-action btn-view">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <button id="toggleBtn-{{ $article->id }}" class="btn-action btn-hide" onclick="toggleArticle({{ $article->id }})">
                                        <i id="toggleIcon-{{ $article->id }}" class="fas {{ $article->is_visible ?? true ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        <span id="toggleText-{{ $article->id }}">{{ $article->is_visible ?? true ? 'Ẩn' : 'Hiện' }}</span>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteArticle({{ $article->id }})">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Không có bài báo nào. <a href="#" onclick="fetchNews()">Nhấp để cập nhật tin tức</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Alert Box -->
    <div class="alert-box" id="alertBox"></div>

    <footer class="main-footer">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ url('/') }}" class="footer-brand"><i class="fas fa-paw"></i>BÁO ĐỐM</a>
                    <p class="footer-desc">Nền tảng tin tức số 1 Việt Nam - Nhanh, chính xác, toàn diện.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h6 class="footer-title">Liên kết nhanh</h6>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.logout') }}">Quản lý</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-end">
                    <h6 class="footer-title">Kết nối</h6>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">&copy; 2025 Báo Đốm. All rights reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showAlert(message, type = 'success') {
            const alertBox = document.getElementById('alertBox');
            alertBox.textContent = message;
            alertBox.className = `alert-box ${type} show`;
            setTimeout(() => {
                alertBox.classList.remove('show');
            }, 3000);
        }

        function deleteArticle(id) {
            if (!confirm('Bạn chắc chắn muốn xóa bài báo này?')) return;

            fetch(`/admin/article/${id}/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showAlert('Bài báo đã được xóa thành công!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.error || 'Lỗi khi xóa bài', 'error');
                }
            })
            .catch(err => showAlert('Lỗi: ' + err.message, 'error'));
        }

        function fetchNews() {
            const btn = document.getElementById('fetchBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';

            fetch('/admin/fetch-news', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> Cập nhật tin';
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Bắt đầu polling scrape status để reload sau khi hoàn tất
                    const start = Date.now();
                    const timeout = 1000 * 60 * 10; // 10 phút max
                    const poll = setInterval(() => {
                        fetch('/admin/scrape-status')
                        .then(r => r.json())
                        .then(s => {
                            if (!s || !s.status) return;
                            if (s.status !== 'running') {
                                clearInterval(poll);
                                showAlert('Đã hoàn tất cập nhật', 'success');
                                setTimeout(() => location.reload(), 800);
                            } else {
                                // vẫn đang chạy, do nothing (could update small spinner)
                            }
                        })
                        .catch(() => {
                            // ignore transient errors
                        });

                        if (Date.now() - start > timeout) {
                            clearInterval(poll);
                            showAlert('Quá thời gian chờ cập nhật, vui lòng kiểm tra bằng nút Chi tiết', 'error');
                        }
                    }, 2000);
                } else {
                    showAlert(data.error || 'Lỗi cập nhật', 'error');
                }
            })
            .catch(err => {
                btn.classList.remove('loading');
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> Cập nhật tin';
                showAlert('Lỗi: ' + err.message, 'error');
            });
        }

        function toggleArticle(id) {
            const btn = document.getElementById(`toggleBtn-${id}`);
            const icon = document.getElementById(`toggleIcon-${id}`);
            const text = document.getElementById(`toggleText-${id}`);
            const status = document.getElementById(`status-${id}`);
            
            btn.disabled = true;
            
            fetch(`/admin/article/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const isVisible = data.is_visible;
                    
                    // Update status text
                    status.textContent = isVisible ? 'Đang hiển thị' : 'Đang ẩn';
                    
                    // Update button classes
                    btn.classList.toggle('btn-hide', isVisible);
                    btn.classList.toggle('btn-show', !isVisible);
                    
                    // Update icon
                    icon.classList.toggle('fa-eye-slash', isVisible);
                    icon.classList.toggle('fa-eye', !isVisible);
                    
                    // Update button text
                    text.textContent = isVisible ? 'Ẩn' : 'Hiện';
                }
                btn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }

        function fetchNewsDetails() {
            const btn = document.getElementById('fetchDetailsBtn');
            btn.disabled = true;
            fetch('/admin/fetch-news-details', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.error || 'Không thể mở cửa sổ chi tiết', 'error');
                }
                btn.disabled = false;
            })
            .catch(err => {
                btn.disabled = false;
                showAlert('Lỗi: ' + err.message, 'error');
            });
        }
    </script>
</body>
</html>
