{{-- Kiểm tra để tránh lỗi foreach null --}}
@if(isset($replies) && $replies->count() > 0)
    @foreach($replies as $reply)
        <div class="ms-4 mt-3 ps-3 border-start">
            <div class="d-flex">
                {{-- Avatar tự động theo tên --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->name) }}&background=random" 
                     class="rounded-circle" width="30" height="30">
                
                <div class="ms-2 w-100">
                    <div class="bg-light p-2 rounded">
                        <h6 class="fw-bold mb-1 small">{{ $reply->name }}</h6>
                        <p class="mb-0 small text-dark">{{ $reply->content }}</p>
                    </div>
                    
                    <div class="mt-1 d-flex align-items-center">
                        {{-- Nút Thích cho câu trả lời --}}
                        <a href="{{ route('comment.like', $reply->id) }}" class="btn-like small text-muted text-decoration-none">
                            <i class="far fa-thumbs-up"></i> Thích ({{ $reply->likes }})
                        </a>

                        {{-- Nút Trả lời cho câu trả lời (Trả lời của trả lời) --}}
                        <span class="text-muted small fw-bold ms-3" style="cursor: pointer;" 
                              onclick="toggleReply({{ $reply->id }})">
                            Trả lời
                        </span>

                        <span class="text-muted small ms-3">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Form nhập câu trả lời mới (mặc định ẩn) --}}
            <div id="reply-form-{{ $reply->id }}" class="reply-form mt-2" style="display: none;">
                <form action="{{ route('news.comment', $article->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                    <div class="d-flex gap-2">
                        <input type="text" name="name" class="form-control form-control-sm w-25" placeholder="Tên..." required>
                        <input type="text" name="content" class="form-control form-control-sm" placeholder="Viết câu trả lời..." required>
                        <button class="btn btn-warning btn-sm text-white">Gửi</button>
                    </div>
                </form>
            </div>

            {{-- Đệ quy: Tự gọi lại chính nó nếu có các cấp trả lời sâu hơn --}}
            @if($reply->replies && $reply->replies->count() > 0)
                @include('news.partials.comment_replies', [
                    'replies' => $reply->replies, 
                    'level' => $level + 1, 
                    'article' => $article
                ])
            @endif
        </div>
    @endforeach
@endif