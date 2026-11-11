<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bài viết của tôi</h2>
            <div>
                <a href="{{ route('home') }}" class="btn btn-secondary">Trang chủ</a>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Viết bài mới</a>
                <a href="{{ route('posts.trash') }}" class="btn btn-outline-danger">Thùng rác</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td><a href="{{ route('posts.show', $post->slug) }}" target="_blank">{{ $post->title }}</a></td>
                                <td>{{ $post->category->name ?? '' }}</td>
                                <td>
                                    <span class="badge bg-{{ $post->status == 'published' ? 'success' : ($post->status == 'scheduled' ? 'warning' : 'secondary') }}">
                                        {{ $post->status }}
                                    </span>
                                </td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Chuyển bài viết vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Bạn chưa có bài viết nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</body>
</html>