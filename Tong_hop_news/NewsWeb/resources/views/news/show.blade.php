<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Báo Đốm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Helvetica', sans-serif; background-color: #f9f9f9; }
        .article-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .article-title { font-weight: 800; color: #333; font-size: 2rem; line-height: 1.3; }
        .article-meta { color: #666; font-size: 0.9rem; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .article-content { font-size: 1.1rem; line-height: 1.8; color: #222; text-align: justify; }
        .article-content p { margin-bottom: 1.5rem; }
        .featured-img { width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px; margin-bottom: 30px; }
        .related-news { margin-top: 50px; padding-top: 20px; border-top: 3px solid #ff6b00; }
        .back-btn { text-decoration: none; color: #555; font-weight: bold; }
        .back-btn:hover { color: #ff6b00; }
    </style>
</head>
<body>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-3">
                    <a href="{{ url('/') }}" class="back-btn"><i class="fas fa-arrow-left"></i> Quay lại trang chủ</a>
                </div>

                <div class="article-container">
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">{{ $article->source->name ?? 'Tổng hợp' }}</span>
                        @foreach($article->categories as $cat)
                            <span class="badge bg-light text-secondary border">{{ $cat->name }}</span>
                        @endforeach
                    </div>

                    <h1 class="article-title mb-3">{{ $article->title }}</h1>

                    <div class="article-meta d-flex justify-content-between">
                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($article->published_date)->format('H:i, d/m/Y') }}</span>
                        <span><i class="fas fa-link"></i> <a href="{{ $article->link }}" target="_blank" class="text-muted">Link gốc</a></span>
                    </div>

                    @if($article->image_url)
                        <img src="{{ $article->image_url }}" class="featured-img" onerror="this.style.display='none'">
                    @endif

                    <div class="article-content">
                        {{-- Hàm nl2br giúp chuyển dấu xuống dòng thành thẻ <br> để văn bản không bị dính cục --}}
                      <div class="article-content-body">
    {!! $article->content !!}
</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>