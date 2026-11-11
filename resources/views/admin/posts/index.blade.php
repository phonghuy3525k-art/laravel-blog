<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bài viết hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm me-2">Tổng quan</a>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Về trang chủ</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Quản lý toàn bộ bài viết</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Danh mục</th>
                            <th>Ngày đăng</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>
                                    <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="text-decoration-none">
                                        {{ Str::limit($post->title, 40) }}
                                    </a>
                                </td>
                                <td>{{ $post->author->name ?? 'Ẩn danh' }}</td>
                                <td><span class="badge bg-secondary">{{ $post->category->name ?? 'Chưa phân loại' }}</span></td>
                                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</body>
</html>