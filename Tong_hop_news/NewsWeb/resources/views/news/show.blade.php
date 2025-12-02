  <!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer">
    <title>{{ $article->title }} - Báo Đốm</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --primary: #ff6b00; --secondary: #2c3e50; --bg-gray: #f9f9f9; }
        body { background-color: var(--bg-gray); font-family: 'Roboto', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        
        .main-container { max-width: 800px; margin: 30px auto; flex: 1; } /* flex: 1 để đẩy footer xuống đáy */
        .article-box { background: white; padding: 40px 50px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        
        .article-title { font-family: 'Merriweather', serif; font-size: 2.4rem; font-weight: 700; color: #222; line-height: 1.3; margin-bottom: 15px; }
        
        .article-meta { 
            color: #777; font-size: 0.9rem; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; 
            display: flex; justify-content: space-between; align-items: center; 
        }

        /* NỘI DUNG CHÍNH */
        .article-content { 
            font-family: 'Merriweather', serif; font-size: 1.2rem; line-height: 1.8; color: #2a2a2a; text-align: justify; 
        }
        .article-content img { max-width: 100%; height: auto; margin: 25px auto; display: block; border-radius: 4px; }
        .article-content figure { margin: 30px auto; text-align: center; }
        
        /* Chú thích ảnh */
        .article-content figcaption { 
            font-family: 'Roboto', sans-serif; font-size: 0.95rem; color: #222; background: #f0f0f0; 
            padding: 10px 15px; margin-top: 10px; font-style: italic; border-radius: 0 0 5px 5px; 
            border-top: 1px solid #ddd; line-height: 1.4;
        }

        /* Tương tác */
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn-love-small { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e5e5e5; padding: 8px 16px; border-radius: 20px; color: #555; font-weight: 600; text-decoration: none; transition: 0.2s; }
        .comment-sec { margin-top: 20px; background: #fff; padding: 30px; border-radius: 8px; border-top: 4px solid var(--primary); }
        .btn-like { color: #666; font-size: 0.85rem; font-weight: 600; margin-right: 15px; text-decoration: none; }
        .btn-back { color: #666; font-weight: 600; text-decoration: none; margin-bottom: 20px; display: inline-block; }

        /* --- CSS FOOTER MỚI --- */
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

    <script>
        function toggleReply(id) {
            var form = document.getElementById('reply-form-' + id);
            document.querySelectorAll('.reply-form').forEach(f => {
                 if (f.id !== 'reply-form-' + id) f.style.display = 'none';
            });
            form.style.display = (form.style.display === 'block') ? 'none' : 'block';
        }
        
        document.addEventListener('DOMContentLoaded', (event) => {
            const contentDiv = document.querySelector('.article-content');
            if (contentDiv) {
                const firstImageOrFigure = contentDiv.querySelector('img, figure'); 
                if (firstImageOrFigure) firstImageOrFigure.remove();
            }
        });
    </script>
</head>
<body>

    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="color: var(--primary); font-size: 1.5rem;">
                <i class="fas fa-paw"></i> BÁO ĐỐM
            </a>
        </div>
    </nav>

    <div class="container main-container">
        <a href="{{ url('/') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>

        <div class="article-box">
            <div class="mb-3">
                <span class="badge bg-danger">{{ $article->source->name ?? 'Tổng hợp' }}</span>
                @foreach($article->categories as $cat)
                    <span class="badge bg-warning text-dark">{{ $cat->name }}</span>
                @endforeach
            </div>

            <h1 class="article-title">{{ $article->title }}</h1>

            <div class="article-meta">
                <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($article->published_date)->format('H:i, d/m/Y') }}</span>
                <a href="{{ $article->link }}" target="_blank" class="link-source-small">Xem bài gốc <i class="fas fa-external-link-alt"></i></a>
            </div>

            @if($article->image_url)
                @php
                    $proxyUrl = 'https://images.weserv.nl/?url=';
                    $proxiedFeaturedImageUrl = $proxyUrl . urlencode($article->image_url);
                @endphp
                <img src="{{ $proxiedFeaturedImageUrl }}" class="img-fluid w-100 mb-4 rounded" onerror="this.style.display='none'" style="max-height: 450px; object-fit: cover;">
            @endif

            <div class="article-content">
                {!! $article->content !!}
            </div>

            <div class="action-bar">
                <a href="{{ route('article.love', $article->id) }}" class="btn-love-small" title="Yêu thích">
                    <i class="fas fa-heart"></i> Yêu thích ({{ $article->loves }})
                </a>
                <div class="d-flex align-items-center">
                    <span class="text-muted small me-2">Chia sẻ:</span>
                    <a href="#" class="btn-share"><i class="fab fa-facebook fa-lg"></i></a>
                </div>
            </div>
        </div>

        <div class="comment-sec">
            <h5 class="fw-bold mb-4">Ý kiến bạn đọc ({{ $article->comments->count() }})</h5>
            
            @if(session('success'))
                <div class="alert alert-success py-2 small">{{ session('success') }}</div>
            @endif

            <form action="{{ route('news.comment', $article->id) }}" method="POST" class="mb-5">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="Tên bạn..." required></div>
                    <div class="col-md-8"><input type="text" name="content" class="form-control" placeholder="Viết bình luận..." required></div>
                </div>
                <div class="text-end mt-2"><button class="btn btn-dark btn-sm px-4">Gửi</button></div>
            </form>

            @foreach($article->comments as $comment)
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=random" class="rounded-circle" width="40" height="40">
                        <div class="ms-3 w-100">
                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold mb-1">{{ $comment->name }}</h6>
                                <p class="mb-0 small text-dark">{{ $comment->content }}</p>
                            </div>
                            <div class="mt-1 ms-2">
                                <a href="{{ route('comment.like', $comment->id) }}" class="btn-like"><i class="far fa-thumbs-up"></i> Thích ({{ $comment->likes }})</a>
                                <span class="text-muted small fw-bold" style="cursor: pointer;" onclick="toggleReply({{ $comment->id }})">Trả lời</span>
                                <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div id="reply-form-{{ $comment->id }}" class="reply-form mt-2 ms-5" style="display: none;">
                        <form action="{{ route('news.comment', $article->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <div class="d-flex gap-2">
                                <input type="text" name="name" class="form-control form-control-sm w-25" placeholder="Tên..." required>
                                <input type="text" name="content" class="form-control form-control-sm" placeholder="Trả lời..." required>
                                <button class="btn btn-warning btn-sm text-white">Gửi</button>
                            </div>
                        </form>
                    </div>

                    @include('news.partials.comment_replies', ['replies' => $comment->replies, 'level' => 1, 'article' => $article])
                </div>
            @endforeach
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
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

               

                <div class="col-md-4 mb-4 text-md">
                    <h5 class="footer-title">Liên Hệ Nhóm 8</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2 text-warning"></i> Đại học Công Nghệ TP.HCM</li>
                        <li><i class="fas fa-envelope me-2 text-warning"></i> contact@baodom.com</li>
                        <li><i class="fas fa-phone-alt me-2 text-warning"></i> (028) 1234 5678</li>
                    </ul>
                    <div class="mt-3">
                        <span class="d-block text-white mb-2 fw-bold">Đăng ký nhận tin:</span>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-sm" placeholder="Email của bạn...">
                            <button class="btn btn-primary btn-sm" style="background: var(--primary); border: none;">Gửi</button>
                        </div>
                    </div>
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