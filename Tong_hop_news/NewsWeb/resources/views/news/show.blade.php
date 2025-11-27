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
        body { background-color: var(--bg-gray); font-family: 'Roboto', sans-serif; }
        
        .main-container { max-width: 800px; margin: 30px auto; }
        .article-box { background: white; padding: 40px 50px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); position: relative; }
        
        .article-title { font-family: 'Merriweather', serif; font-size: 2.2rem; font-weight: 900; color: #333; margin-bottom: 20px; line-height: 1.4; }
        
        .article-meta { 
            color: #777; font-size: 0.9rem; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 30px; 
            display: flex; justify-content: space-between; align-items: center; 
        }
        .link-source-small { color: #555; text-decoration: none; font-weight: 600; border-bottom: 1px dotted #999; }
        .link-source-small:hover { color: var(--primary); border-bottom-color: var(--primary); }

        .article-content { font-family: 'Merriweather', serif; font-size: 1.2rem; line-height: 1.8; color: #2a2a2a; text-align: justify; }
        .article-content img { max-width: 100%; height: auto; margin: 25px auto; display: block; border-radius: 4px; }
        .article-content figcaption { text-align: center; font-size: 0.9rem; color: #666; font-style: italic; background: #f8f8f8; padding: 8px; margin-top: 5px; }

        /* Action Bar */
        .action-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn-love-small {
            display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e5e5e5;
            padding: 8px 16px; border-radius: 20px; color: #555; font-weight: 600; text-decoration: none; transition: 0.2s;
        }
        .btn-love-small:hover { background: #fff0f3; border-color: #ffc1ce; color: #e91e63; }
        .btn-share { color: #666; text-decoration: none; font-size: 0.9rem; margin-left: 15px; }
        .btn-share:hover { color: #3b5998; }

        /* Comment Section */
        .comment-sec { margin-top: 20px; background: #fff; padding: 30px; border-radius: 8px; border-top: 4px solid var(--primary); }
        .reply-form { display: none; margin-top: 10px; margin-left: 50px; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .btn-like { cursor: pointer; color: #666; text-decoration: none; font-size: 0.85rem; font-weight: 600; margin-right: 15px; }
        .btn-like:hover { color: #0d6efd; }
        .btn-reply { cursor: pointer; color: #666; font-size: 0.85rem; font-weight: 600; }
        .btn-reply:hover { color: var(--primary); }
        .btn-back { text-decoration: none; color: #666; font-weight: 600; margin-bottom: 20px; display: inline-block; }
        .btn-back:hover { color: var(--primary); transform: translateX(-5px); transition: 0.3s; }
    </style>

    <script>
        function toggleReply(id) {
            var form = document.getElementById('reply-form-' + id);
            form.style.display = (form.style.display === 'block') ? 'none' : 'block';
        }
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

    <div class="main-container">
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
                <a href="{{ $article->link }}" target="_blank" class="link-source-small">
                    Xem bài gốc <i class="fas fa-external-link-alt"></i>
                </a>
            </div>

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
                    <a href="#" class="btn-share"><i class="fab fa-twitter fa-lg"></i></a>
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
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Tên bạn..." required>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="content" class="form-control" placeholder="Viết bình luận..." required>
                    </div>
                </div>
                <div class="text-end mt-2">
                    <button class="btn btn-dark btn-sm px-4">Gửi</button>
                </div>
            </form>

            @foreach($article->comments as $comment)
                <div class="mb-4">
                    <div class="d-flex">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=random&size=40" class="rounded-circle" width="40">
                        <div class="ms-3 w-100">
                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold mb-1">{{ $comment->name }}</h6>
                                <p class="mb-0 small text-dark">{{ $comment->content }}</p>
                            </div>
                            <div class="mt-1 ms-2">
                                <a href="{{ route('comment.like', $comment->id) }}" class="btn-like"><i class="far fa-thumbs-up"></i> Thích ({{ $comment->likes }})</a>
                                <span class="btn-reply" onclick="toggleReply({{ $comment->id }})">Trả lời</span>
                                <span class="text-muted small ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div id="reply-form-{{ $comment->id }}" class="reply-form">
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
                    @foreach($comment->replies as $reply)
                        <div class="d-flex mt-2 ms-5">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->name) }}&background=random" class="rounded-circle" width="30">
                            <div class="ms-2">
                                <div class="bg-light p-2 px-3 rounded"><span class="fw-bold small">{{ $reply->name }}</span> <span class="small">{{ $reply->content }}</span></div>
                                <a href="{{ route('comment.like', $reply->id) }}" class="btn-like small ms-2"><i class="far fa-thumbs-up"></i> {{ $reply->likes }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>