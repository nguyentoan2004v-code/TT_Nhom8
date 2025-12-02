<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer">
    <title>Báo Đốm - Tin tức nhanh & chính xác</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --primary: #ff6b00; --secondary: #2c3e50; --bg-gray: #f7f7f7; }
        
        /* Cấu trúc Flexbox để Footer luôn ở đáy */
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
        
        .brand-logo { font-family: 'Merriweather', serif; font-weight: 900; font-size: 1.6rem; color: var(--primary); text-decoration: none; margin-right: 30px; white-space: nowrap; display: flex; align-items: center; }
        
        .nav-menu { flex-grow: 1; overflow-x: auto; white-space: nowrap; scrollbar-width: none; height: 70px; margin-right: 20px; display: flex; align-items: center; }
        .nav-menu::-webkit-scrollbar { display: none; }
        .nav-menu a { text-decoration: none; color: #444; font-weight: 600; font-size: 0.9rem; padding: 0 15px; height: 100%; display: flex; align-items: center; border-bottom: 3px solid transparent; transition: 0.2s; text-transform: uppercase; }
        .nav-menu a:hover, .nav-menu a.active { color: var(--primary); border-bottom-color: var(--primary); background-color: #fffaf5; }

        .search-form { flex-shrink: 0; position: relative; width: 220px; }
        .search-input { border: 1px solid #ddd; border-radius: 20px; padding: 6px 15px 6px 35px; font-size: 0.85rem; width: 100%; background: #f9f9f9; transition: 0.3s; }
        .search-icon-btn { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; border: none; background: none; pointer-events: none; }

        /* Grid Tin Tức */
        .main-content-wrap { flex: 1; } /* Đẩy footer xuống */
        .section-title { font-weight: 800; color: #222; border-left: 5px solid var(--primary); padding-left: 15px; margin: 10px 0 25px; text-transform: uppercase; font-size: 1.1rem; }
        .news-card { background: #fff; border: none; border-radius: 10px; overflow: hidden; transition: transform 0.3s; height: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.03); }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        .card-img-wrap { position: relative; padding-top: 60%; overflow: hidden; background: #eee; }
        .card-img-top { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .news-card:hover .card-img-top { transform: scale(1.1); }

        /* Fallback Image Style */
        .fallback-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: rgba(255,255,255,0.9); background: linear-gradient(135deg, #bdc3c7, #2c3e50); text-align: center; z-index: 1; }
        .fallback-img i { font-size: 3rem; margin-bottom: 8px; display: block; }
        .fallback-img span { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 15px; }
        .bg-the-thao { background: linear-gradient(135deg, #11998e, #38ef7d); }

        .source-tag { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: #fff; padding: 2px 8px; font-size: 0.7rem; border-radius: 4px; font-weight: bold; z-index: 5; }
        
        .card-body { padding: 15px; display: flex; flex-direction: column; }
        .news-title { font-size: 1rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .news-title a { text-decoration: none; color: #333; transition: 0.2s; }
        .news-title a:hover { color: var(--primary); }
        .news-meta { margin-top: auto; font-size: 0.8rem; color: #888; border-top: 1px dashed #eee; padding-top: 10px; display: flex; justify-content: space-between; }

        /* Sidebar */
        .sidebar-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .sidebar-head { font-weight: 700; text-transform: uppercase; font-size: 0.9rem; border-left: 4px solid var(--primary); padding-left: 10px; margin-bottom: 15px; color: #222; }
        .list-vertical li a { text-decoration: none; color: #555; font-weight: 600; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        
        /* --- CSS FOOTER MỚI (Đã đồng bộ) --- */
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
        .footer-desc { line-height: 1.6; margin-bottom: 20px; }
        .footer-title {
            color: #fff;
            font-weight: 700;
            margin-bottom: 25px;
            text-transform: uppercase;
            font-size: 1rem;
            letter-spacing: 0.5px;
            position: relative;
        }
        .footer-title::after {
            content: ''; position: absolute; left: 0; bottom: -8px;
            width: 40px; height: 3px; background: var(--primary);
        }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: #b0b0b0; text-decoration: none; transition: 0.2s; }
        .footer-links a:hover { color: var(--primary); padding-left: 5px; }
        
        .social-links a {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.1);
            display: inline-flex; justify-content: center; align-items: center;
            border-radius: 50%;
            color: #fff;
            margin-right: 10px;
            transition: 0.3s;
            text-decoration: none;
        }
        .social-links a:hover { background: var(--primary); transform: translateY(-3px); }
        
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

    @php
        $icons = [
            'Chính trị - Xã hội' => 'fa-landmark', 'Kinh doanh' => 'fa-chart-line', 'Giáo dục' => 'fa-graduation-cap',
            'Thể thao' => 'fa-futbol', 'Pháp luật' => 'fa-gavel',
            'Giải trí' => 'fa-music', 'Công nghệ' => 'fa-microchip',
            'Sức khỏe' => 'fa-heartbeat', 'Đời sống' => 'fa-coffee',
        ];
        $proxyUrl = 'https://images.weserv.nl/?url=';
        $fallbackColors = [
            'Thể thao' => 'bg-the-thao', 'Pháp luật' => 'bg-phap-luat', 'Kinh doanh' => 'bg-cong-nghe'
        ];
    @endphp

    <nav class="navbar fixed-top">
        <div class="header-container">
            <a href="{{ url('/') }}" class="brand-logo"><i class="fas fa-paw brand-icon me-2"></i>BÁO ĐỐM</a>

            <div class="nav-menu">
                <a href="{{ url('/') }}" class="{{ !isset($currentCategory) && !isset($currentSource) ? 'active' : '' }}">
                    <i class="fas fa-home me-1"></i> Tất cả
                </a>
                @if(isset($categories))
                    @foreach($categories as $cat)
                        <a href="{{ route('news.category', $cat->id) }}" 
                           class="{{ (isset($currentCategory) && $currentCategory->id == $cat->id) ? 'active' : '' }}">
                           <i class="fas {{ $icons[$cat->name] ?? 'fa-folder' }} me-2 opacity-75"></i>
                           {{ $cat->name }}
                        </a>
                    @endforeach
                @endif
            </div>

            <form action="{{ route('news.index') }}" method="GET" class="search-form">
                <button type="submit" class="search-icon-btn"><i class="fas fa-search"></i></button>
                <input type="text" name="search" class="search-input" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </form>
        </div>
    </nav>

    <div class="container mt-4 main-content-wrap">
        <div class="row">
            
            <div class="col-lg-9">
                <div class="section-title">
                    @if(isset($currentSource)) <i class="fas fa-newspaper me-2"></i>Tin từ: <span class="text-danger">{{ $currentSource->name }}</span>
                    @elseif(isset($currentCategory)) <i class="fas {{ $icons[$currentCategory->name] ?? 'fa-folder-open' }} me-2"></i>{{ $currentCategory->name }}
                    @elseif(request('search')) <i class="fas fa-search me-2"></i>Kết quả: "{{ request('search') }}"
                    @else <i class="fas fa-bolt me-2"></i>Tin Mới Cập Nhật @endif
                </div>

                @if(isset($articles) && $articles->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach ($articles as $article)
                        
                        @php
                            $firstCat = $article->categories->first();
                            $catName = $firstCat ? $firstCat->name : 'Tin tức';
                            $iconClass = $icons[$catName] ?? 'fa-newspaper';
                            $bgClass = $fallbackColors[$catName] ?? 'bg-default';
                            $finalImageUrl = !empty($article->image_url) ? $proxyUrl . urlencode($article->image_url) : null;
                        @endphp

                        <div class="col">
                            <div class="news-card h-100">
                                <div class="card-img-wrap">
                                    <a href="{{ route('news.show', $article->id) }}">
                                        @if($finalImageUrl)
                                            <img src="{{ $finalImageUrl }}" class="card-img-top" 
                                                 onerror="this.style.display='none'; document.getElementById('fallback-home-{{$article->id}}').style.display='flex'">
                                            
                                            <div id="fallback-home-{{$article->id}}" class="card-img-top fallback-img {{ $bgClass }}" style="display:none;">
                                                <i class="fas {{ $iconClass }} fa-3x mb-2 opacity-50"></i>
                                                <span class="small fw-bold text-uppercase opacity-75">{{ $catName }}</span>
                                            </div>
                                        @else
                                            <div class="fallback-img {{ $bgClass }}">
                                                <i class="fas {{ $iconClass }} fa-3x mb-2 opacity-50"></i>
                                                <span class="small fw-bold text-uppercase opacity-75">{{ $catName }}</span>
                                            </div>
                                        @endif
                                    </a>
                                    <span class="source-tag">{{ $article->source->name ?? 'News' }}</span>
                                </div>

                                <div class="card-body">
                                    <h5 class="news-title">
                                        <a href="{{ route('news.show', $article->id) }}">
                                            @if(request('search')) {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<span style="background-color: yellow;">$1</span>', $article->title) !!}
                                            @else {{ $article->title }} @endif
                                        </a>
                                    </h5>
                                    <div class="news-meta">
                                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($article->published_date)->format('d/m H:i') }}</span>
                                        <span class="text-primary cursor-pointer" onclick="window.location='{{ route('news.show', $article->id) }}'">Xem <i class="fas fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-5 mb-5">{{ $articles->links() }}</div>
                @else
                    <div class="alert alert-light text-center py-5 border"><p class="text-muted">Không tìm thấy tin nào.</p></div>
                @endif
            </div>

            <div class="col-lg-3 ps-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <div class="sidebar-box">
                        <div class="sidebar-head">Nguồn Báo</div>
                        
                        @if(isset($sources) && $sources->count() > 0)
                            <ul class="list-vertical m-0 p-0">
                                <li>
                                    <a href="{{ url('/') }}" class="{{ !isset($currentSource) ? 'text-warning' : '' }}">
                                        <span><i class="fas fa-globe me-2"></i>Tất cả nguồn</span>
                                    </a>
                                </li>
                                @foreach($sources as $src)
                                <li>
                                    <a href="{{ route('news.source', $src->id) }}" class="{{ (isset($currentSource) && $currentSource->id == $src->id) ? 'text-warning' : '' }}">
                                        <span>{{ $src->name }}</span>
                                        <span class="count-badge">{{ $src->articles()->count() }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">Đang cập nhật nguồn...</p>
                        @endif
                    </div>

                    <div class="sidebar-box text-center text-white" style="background: linear-gradient(45deg, #ff6b00, #e67e22); border:none;">
                        <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                        <h6 class="fw-bold">App Báo Đốm</h6>
                        <button class="btn btn-sm btn-light w-100 fw-bold text-warning mt-2">Tải Ngay</button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-4">
                    <a href="{{ url('/') }}" class="footer-brand"><i class="fas fa-paw"></i> BÁO ĐỐM</a>
                    <p class="footer-desc">
                        Hệ thống tổng hợp tin tức tự động thông minh, mang đến cho bạn những thông tin nóng hổi, chính xác và đa chiều nhất từ các nguồn báo chí uy tín hàng đầu Việt Nam.
                    </p>
                    <div class="social-links mt-3">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.facebook.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                

                <div class="col-md-4 mb-4">
                    <h5 class="footer-title">Liên Hệ Nhóm 8</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2 text-warning"></i> Đại học Công Nghệ TP.HCM</li>
                        <li><i class="fas fa-envelope me-2 text-warning"></i> contact@baodom.com</li>
                        <li><i class="fas fa-phone-alt me-2 text-warning"></i> (039) 6046 671</li>
                    </ul>
                    
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-md-start">
                        © 2025 <strong>Báo Đốm</strong>. All Rights Reserved.
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-white text-decoration-none mx-2 small">Điều khoản</a>
                        <a href="#" class="text-white text-decoration-none mx-2 small">Bảo mật</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>