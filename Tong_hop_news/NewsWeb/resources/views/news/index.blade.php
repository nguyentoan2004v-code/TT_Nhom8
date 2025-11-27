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
        body { font-family: 'Roboto', sans-serif; background-color: var(--bg-gray); color: #333; padding-top: 130px; }

        /* --- HEADER 2 TẦNG --- */
        .fixed-header { position: fixed; top: 0; left: 0; right: 0; z-index: 1030; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

        /* Tầng 1: Logo & Tìm kiếm */
        .top-header { height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; border-bottom: 1px solid #f0f0f0; }
        .brand-logo { font-family: 'Merriweather', serif; font-weight: 900; font-size: 1.8rem; color: var(--primary); text-decoration: none; display: flex; align-items: center; }
        
        .search-wrapper { position: relative; width: 250px; }
        .search-input { border: 1px solid #ddd; border-radius: 20px; padding: 6px 15px 6px 35px; font-size: 0.9rem; width: 100%; background: #f9f9f9; }
        .search-icon-btn { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; border: none; background: none; pointer-events: none; }

        /* Tầng 2: Menu Danh mục */
        .bottom-header { height: 50px; display: flex; align-items: center; border-bottom: 2px solid var(--primary); background: #fff; }
        .cat-menu { display: flex; align-items: center; overflow-x: auto; white-space: nowrap; scrollbar-width: none; height: 100%; width: 100%; padding: 0 20px; }
        .cat-menu::-webkit-scrollbar { display: none; }
        
        .cat-menu a { 
            text-decoration: none; color: #444; font-weight: 600; font-size: 0.9rem; 
            padding: 0 15px; height: 100%; display: flex; align-items: center; 
            border-bottom: 3px solid transparent; transition: 0.2s; text-transform: uppercase; 
        }
        .cat-menu a:hover, .cat-menu a.active { color: var(--primary); border-bottom-color: var(--primary); background-color: #fffaf5; }

        /* --- GRID TIN TỨC --- */
        .section-title { font-weight: 800; color: #222; border-left: 5px solid var(--primary); padding-left: 15px; margin: 20px 0 25px; text-transform: uppercase; font-size: 1.1rem; }
        
        .news-card { background: #fff; border: none; border-radius: 10px; overflow: hidden; transition: 0.3s; height: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.03); }
        .news-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .card-img-wrap { position: relative; padding-top: 60%; overflow: hidden; background: #eee; }
        .card-img-top { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .news-card:hover .card-img-top { transform: scale(1.1); }

        /* Ảnh thay thế (Fallback) */
        .fallback-img {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.9); background: linear-gradient(135deg, #bdc3c7, #2c3e50);
            text-align: center; z-index: 1;
        }
        .fallback-img i { font-size: 3rem; margin-bottom: 8px; display: block; }
        .fallback-img span { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 15px; }

        /* Màu nền danh mục */
        .bg-the-thao { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .bg-phap-luat { background: linear-gradient(135deg, #8E2DE2, #4A00E0); }
        .bg-giai-tri { background: linear-gradient(135deg, #fc4a1a, #f7b733); }
        .bg-cong-nghe { background: linear-gradient(135deg, #302b63, #24243e); }
        .bg-suc-khoe { background: linear-gradient(135deg, #ff416c, #ff4b2b); }

        .source-tag { position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.7); color: #fff; padding: 2px 8px; font-size: 0.7rem; border-radius: 4px; font-weight: bold; z-index: 5; }
        
        .card-body { padding: 15px; display: flex; flex-direction: column; }
        .news-title { font-size: 1rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .news-title a { text-decoration: none; color: #333; transition: 0.2s; }
        .news-title a:hover { color: var(--primary); }
        .news-meta { margin-top: auto; font-size: 0.8rem; color: #888; border-top: 1px dashed #eee; padding-top: 10px; display: flex; justify-content: space-between; }

        /* --- SIDEBAR (NGUỒN BÁO) --- */
        .sidebar-box { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .sidebar-head { font-weight: 700; text-transform: uppercase; font-size: 0.9rem; border-left: 4px solid var(--primary); padding-left: 10px; margin-bottom: 15px; color: #222; }
        
        .list-vertical li { list-style: none; padding: 10px 0; border-bottom: 1px dashed #eee; }
        .list-vertical li a { text-decoration: none; color: #555; font-weight: 600; display: flex; justify-content: space-between; align-items: center; transition: 0.2s; }
        .list-vertical li a:hover { color: var(--primary); padding-left: 5px; }
        .count-badge { background: #eee; color: #555; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; }
        .list-vertical li a:hover .count-badge { background: var(--primary); color: #fff; }
    </style>
</head>
<body>

    @php
        $icons = [
            'Chính trị - Xã hội' => 'fa-landmark',
            'Kinh doanh' => 'fa-chart-line', 'Giáo dục' => 'fa-graduation-cap',
            'Thể thao' => 'fa-futbol', 'Pháp luật' => 'fa-gavel',
            'Giải trí' => 'fa-music', 'Công nghệ' => 'fa-microchip',
            'Sức khỏe' => 'fa-heartbeat', 'Đời sống' => 'fa-coffee',
        ];
    @endphp

    <div class="fixed-header">
        <div class="container">
            <div class="top-header">
                <a href="{{ url('/') }}" class="brand-logo"><i class="fas fa-paw brand-icon me-2"></i>BÁO ĐỐM</a>
                <form action="{{ route('news.index') }}" method="GET" class="search-wrapper">
                    <button type="submit" class="search-icon-btn"><i class="fas fa-search"></i></button>
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                </form>
            </div>

            <div class="bottom-header">
                <div class="cat-menu">
                    <a href="{{ url('/') }}" class="{{ !isset($currentCategory) && !isset($currentSource) ? 'active' : '' }}">
                        <i class="fas fa-home me-1"></i> Tất cả
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('news.category', $cat->id) }}" class="{{ (isset($currentCategory) && $currentCategory->id == $cat->id) ? 'active' : '' }}">
                           <i class="fas {{ $icons[$cat->name] ?? 'fa-folder' }} me-2 opacity-75"></i>
                           {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            
            <div class="col-lg-9">
                <div class="section-title">
                    @if(isset($currentSource)) <i class="fas fa-newspaper me-2"></i>Nguồn: <span class="text-danger">{{ $currentSource->name }}</span>
                    @elseif(isset($currentCategory)) <i class="fas fa-folder-open me-2"></i>Mục: {{ $currentCategory->name }}
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
                            
                            $bgClass = '';
                            if(in_array($catName, ['Thể thao', 'Sức khỏe'])) $bgClass = 'bg-the-thao';
                            elseif(in_array($catName, ['Pháp luật', 'Chính trị - Xã hội'])) $bgClass = 'bg-phap-luat';
                            elseif(in_array($catName, ['Giải trí', 'Đời sống'])) $bgClass = 'bg-giai-tri';
                            elseif(in_array($catName, ['Công nghệ'])) $bgClass = 'bg-cong-nghe';
                        @endphp

                        <div class="col">
                            <div class="news-card h-100">
                                <div class="card-img-wrap">
                                    <a href="{{ route('news.show', $article->id) }}" class="d-block h-100 w-100">
                                        @if(!empty($article->image_url))
                                            <img src="{{ $article->image_url }}" class="card-img-top" 
                                                 onerror="this.style.display='none'; document.getElementById('fallback-home-{{$article->id}}').style.display='flex'">
                                            
                                            <div id="fallback-home-{{$article->id}}" class="fallback-img {{ $bgClass }}" style="display:none;">
                                                <i class="fas {{ $iconClass }}"></i><span>{{ $catName }}</span>
                                            </div>
                                        @else
                                            <div class="fallback-img {{ $bgClass }}">
                                                <i class="fas {{ $iconClass }}"></i><span>{{ $catName }}</span>
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
                <div class="sticky-top" style="top: 160px;">
                    
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

    <footer class="bg-white border-top py-4 mt-5 text-center">
        <div class="container">
            <div class="brand-logo justify-content-center mb-2" style="font-size: 1.4rem;"><i class="fas fa-paw brand-icon"></i> BÁO ĐỐM</div>
            <p class="text-muted small mb-0">© 2025 Tổng hợp tin tức tự động.</p>
        </div>
    </footer>

</body>
</html>