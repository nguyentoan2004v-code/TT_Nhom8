<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Đốm - Tin Tức Tổng Hợp</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-color: #d35400; --secondary-color: #2c3e50; --bg-color: #f8f9fa; }
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-color); color: #333; }
        .navbar { background: #fff; box-shadow: 0 2px 15px rgba(0,0,0,0.05); padding: 15px 0; border-bottom: 3px solid var(--primary-color); }
        .brand-text { font-weight: 800; font-size: 1.8rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-card { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); margin-bottom: 20px; border-top: 4px solid var(--primary-color); }
        .sidebar-title { font-weight: 700; color: var(--secondary-color); margin-bottom: 20px; font-size: 1.1rem; text-transform: uppercase; }
        .cat-list { list-style: none; padding: 0; margin: 0; }
        .cat-list li a { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; color: #555; text-decoration: none; border-bottom: 1px solid #f0f0f0; transition: all 0.2s; font-weight: 500; }
        .cat-list li:last-child a { border-bottom: none; }
        .cat-list li a:hover { color: var(--primary-color); padding-left: 5px; }
        .cat-list li a.active-cat { color: var(--primary-color); font-weight: 800; background-color: #fff5eb; padding-left: 10px; border-radius: 5px; }
        .badge-count { background: #f0f2f5; color: #777; font-size: 0.75rem; padding: 5px 10px; border-radius: 20px; }
        .news-card { border: none; background: #fff; border-radius: 12px; overflow: hidden; margin-bottom: 25px; transition: 0.3s; box-shadow: 0 5px 10px rgba(0,0,0,0.02); }
        .news-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .article-img-wrapper { height: 220px; overflow: hidden; position: relative; }
        .article-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .news-card:hover .article-img { transform: scale(1.05); }
        .source-badge { position: absolute; top: 15px; left: 15px; background: rgba(0,0,0,0.7); color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .card-body { padding: 25px; display: flex; flex-direction: column; justify-content: center; }
        .news-title a { text-decoration: none; color: var(--secondary-color); font-weight: 700; font-size: 1.25rem; line-height: 1.4; transition: 0.2s; }
        .news-title a:hover { color: var(--primary-color); }
        .news-excerpt { color: #666; font-size: 0.95rem; margin: 10px 0 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .btn-read-more { color: var(--primary-color); font-weight: 600; text-decoration: none; font-size: 0.9rem; }
        .btn-read-more:hover { text-decoration: underline; }
        footer { background: var(--secondary-color); color: #fff; padding: 40px 0; margin-top: 60px; text-align: center; }
    </style>
</head>
<body>

    @php
        $icons = [
            'Chính trị - Xã hội' => 'fa-landmark',
            'Thế giới'           => 'fa-globe-americas',
            'Kinh doanh'         => 'fa-chart-line',
            'Giáo dục'           => 'fa-graduation-cap',
            'Thể thao'           => 'fa-futbol',
            'Pháp luật'          => 'fa-gavel',
            'Giải trí'           => 'fa-music',
            'Công nghệ'          => 'fa-microchip',
            'Sức khỏe'           => 'fa-heartbeat',
            'Đời sống'           => 'fa-coffee',
        ];
    @endphp

    <nav class="navbar sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('news.index') }}">
                <i class="fas fa-paw brand-icon me-2 text-dark"></i>
                <span class="brand-text">BÁO ĐỐM</span>
            </a>
            <div class="d-none d-md-block text-muted small">
                <i class="fas fa-bolt text-warning"></i> Cập nhật tin tức nhanh & chính xác
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            
            <div class="col-lg-4 mb-4">
                <div class="sticky-top" style="top: 100px;">
                    
                    <div class="sidebar-card">
                        <h6 class="sidebar-title"><i class="fas fa-search me-2"></i>Tìm kiếm</h6>
                        <form action="{{ route('news.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Nhập từ khóa..." value="{{ request('search') }}">
                                <button class="btn btn-warning text-white" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="sidebar-card">
                        <h6 class="sidebar-title"><i class="fas fa-layer-group me-2"></i>Chuyên Mục</h6>
                        
                        @if(isset($categories) && $categories->count() > 0)
                            <ul class="cat-list">
                                <li>
                                    <a href="{{ route('news.index') }}" class="{{ !isset($currentCategory) ? 'active-cat' : '' }}">
                                        <span><i class="fas fa-home me-2 text-muted"></i>Tất cả tin</span>
                                    </a>
                                </li>
                                @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('news.category', $cat->id) }}" 
                                       class="{{ (isset($currentCategory) && $currentCategory->id == $cat->id) ? 'active-cat' : '' }}">
                                        <span>
                                            <i class="fas {{ $icons[$cat->name] ?? 'fa-folder' }} me-2 
                                               {{ (isset($currentCategory) && $currentCategory->id == $cat->id) ? '' : 'text-secondary opacity-50' }}">
                                            </i>
                                            {{ $cat->name }}
                                        </span>
                                        <span class="badge-count">{{ $cat->articles()->count() }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">Đang cập nhật danh mục...</p>
                        @endif
                    </div>

                    <div class="sidebar-card mt-4 text-center bg-dark text-white border-0" style="background: linear-gradient(135deg, #2c3e50, #4ca1af);">
                        <i class="fas fa-cloud-sun fa-3x mb-3 text-warning"></i>
                        <h5>Hôm nay: {{ date('d/m/Y') }}</h5>
                        <p class="small text-white-50 mb-4">Chúc bạn một ngày tốt lành!</p>
                    </div>
                </div>
            </div> 

            <div class="col-lg-8 ps-lg-4">
                <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                    <h4 class="fw-bold text-dark m-0">
                        @if(isset($currentCategory))
                            <i class="fas {{ $icons[$currentCategory->name] ?? 'fa-folder-open' }} text-warning me-2"></i>{{ $currentCategory->name }}
                        @elseif(request('search'))
                            <i class="fas fa-search text-warning me-2"></i>Kết quả tìm kiếm: "{{ request('search') }}"
                        @else
                            <i class="far fa-newspaper text-warning me-2"></i>TIN MỚI NHẤT
                        @endif
                    </h4>
                </div>

                @forelse ($articles as $article)
                <div class="card news-card">
                    <div class="row g-0">
                        <div class="col-md-5 article-img-wrapper">
                            <a href="{{ route('news.show', $article->id) }}">
                                @if($article->image_url)
                                    <img src="{{ $article->image_url }}" class="article-img" onerror="this.src='https://via.placeholder.com/400x300?text=Bao+Dom'">
                                @else
                                    <div class="bg-light h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="fas fa-image fa-2x"></i>
                                    </div>
                                @endif
                            </a>
                            <span class="source-badge">{{ $article->source->name ?? 'News' }}</span>
                        </div>

                        <div class="col-md-7">
                            <div class="card-body h-100">
                                <div class="mb-2">
                                    @foreach ($article->categories as $cat)
                                        <span class="badge bg-light text-secondary border me-1">
                                            <i class="fas {{ $icons[$cat->name] ?? 'fa-hashtag' }} me-1 text-warning"></i>
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <h5 class="news-title">
                                    <a href="{{ route('news.show', $article->id) }}">{{ $article->title }}</a>
                                </h5>
                                
                                <p class="news-excerpt">
                                    {{ Str::limit(strip_tags($article->content), 130) }}
                                </p>
                                
                                <div class="news-meta">
                                    <span><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($article->published_date)->diffForHumans() }}</span>
                                    <a href="{{ route('news.show', $article->id) }}" class="btn-read-more">
                                        Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="alert alert-light text-center p-5 shadow-sm">
                        <i class="fas fa-search fa-3x mb-3 text-muted opacity-50"></i>
                        <h5>Không tìm thấy bài viết nào</h5>
                        <p class="text-muted">Vui lòng thử từ khóa khác hoặc chọn mục khác.</p>
                        <a href="{{ route('news.index') }}" class="btn btn-outline-warning mt-2">Về trang chủ</a>
                    </div>
                @endforelse

                <div class="d-flex justify-content-center mt-5 mb-5">
                    {{ $articles->links() }}
                </div>
            </div>

        </div> 
    </div>

    <footer>
        <div class="container">
            <h5 class="fw-bold mb-3">BÁO ĐỐM</h5>
            <p class="mb-0 text-white-50 small">&copy; 2025 Đồ án Chuyên ngành - Nhóm 8.</p>
        </div>
    </footer>

</body>
</html>