<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">BlogCongDong</a>
            <div>
                @auth
                    <!-- Thêm các nút điều hướng cho thành viên -->
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-warning btn-sm me-2">Quản lý bài viết</a>
                    
                    <span class="text-white me-3">Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                    
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Đăng xuất</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm">Đăng ký</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article class="card shadow-sm p-4">
                    <span class="badge bg-secondary align-self-start mb-2">{{ $post->category->name }}</span>
                    <h1 class="mb-3">{{ $post->title }}</h1>
                    <p class="text-muted small">Tác giả: <strong>{{ $post->author->name }}</strong> | Ngày đăng: {{ $post->published_at?->format('d/m/Y H:i') }}</p>
                    
                    <hr>

                    <!-- Hiển thị Ảnh đại diện (Thumbnail) nếu có -->
                    @if($post->thumbnail)
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm" style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    <div class="content mb-4">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Hiển thị Tags -->
                    <div class="mb-4">
                        @foreach($post->tags as $tag)
                            <span class="badge bg-info text-dark">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </article>

                <!-- Khu vực Bình luận -->
                <div class="card shadow-sm mt-4 p-4">
                    <h4>Bình luận ({{ $post->comments->count() }})</h4>
                    <hr>

                    <!-- Form gửi bình luận -->
                    @auth
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" rows="3" class="form-control" placeholder="Viết bình luận của bạn..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Gửi bình luận</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Vui lòng <a href="{{ route('login') }}">Đăng nhập</a> để tham gia bình luận bài viết này.
                        </div>
                    @endauth

                    <!-- Danh sách bình luận -->
                    @forelse($post->comments as $comment)
                        <div class="mb-3 border-bottom pb-2">
                            <strong>{{ $comment->user->name ?? 'Người dùng' }}</strong> 
                            <span class="text-muted small float-end">{{ $comment->created_at->diffForHumans() }}</span>
                            <p class="mb-1 mt-1">{{ $comment->content }}</p>
                        </div>
                    @empty
                        <p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>