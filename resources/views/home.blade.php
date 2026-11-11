<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Cộng Đồng</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">BlogCongDong</a>
            <div>
                @auth
                    <!-- Nếu là tài khoản Admin thì hiện nút truy cập trang Admin -->
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-sm me-2">Quản trị Admin</a>
                    @endif

                    <!-- Nút quản lý bài viết cá nhân -->
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

    <div class="container">
        <div class="row">
            <!-- Cột chính: Danh sách bài viết -->
            <div class="col-lg-8">
                <h2 class="mb-4">Bài viết mới nhất</h2>

                @if($posts->count() > 0)
                   @foreach($posts as $post)
    <div class="card mb-4 shadow-sm">
        <!-- Thêm hiển thị ảnh Thumbnail ở đây -->
        @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 220px; object-fit: cover;">
        @endif

        <div class="card-body">
            <span class="badge bg-secondary mb-2">{{ $post->category->name ?? 'Chưa phân loại' }}</span>
            <h3 class="card-title">
                <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
            </h3>
            <p class="text-muted small">Tác giả: {{ $post->author->name ?? 'Ẩn danh' }} | Ngày đăng: {{ $post->created_at->format('d/m/Y') }}</p>
            <p class="card-text">{{ Str::limit($post->content, 150) }}</p>
            <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
        </div>
    </div>
@endforeach

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="alert alert-info">Không tìm thấy bài viết nào phù hợp.</div>
                @endif
            </div>

            <!-- Cột phụ: Tìm kiếm & Lọc -->
            <div class="col-lg-4">
                <!-- Form Tìm kiếm -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold">Tìm kiếm</div>
                    <div class="card-body">
                        <form action="{{ route('home') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Từ khóa..." value="{{ request('keyword') }}">
                                <button class="btn btn-outline-primary" type="submit">Tìm</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lọc theo Danh mục -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white fw-bold">Danh mục</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action {{ request('category') ? '' : 'active' }}">Tất cả danh mục</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('home', array_merge(request()->all(), ['category' => $cat->id])) }}" 
                               class="list-group-item list-group-item-action {{ request('category') == $cat->id ? 'active' : '' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Lọc theo Thẻ (Tags) -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-bold">Thẻ phổ biến</div>
                    <div class="card-body">
                        @foreach($tags as $tag)
                            <a href="{{ route('home', array_merge(request()->all(), ['tag' => $tag->slug])) }}" 
                               class="badge bg-secondary text-decoration-none m-1 p-2 {{ request('tag') == $tag->slug ? 'bg-primary' : '' }}">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>