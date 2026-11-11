<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang quản trị Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            <div>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm me-2">Về trang chủ</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Tổng quan hệ thống</h2>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5>Bài viết</h5>
                        <h3>{{ $postCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5>Thành viên</h5>
                        <h3>{{ $userCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5>Bình luận</h5>
                        <h3>{{ $commentCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body">
                        <h5>Danh mục</h5>
                        <h3>{{ $categoryCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phím tắt quản trị -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm p-3">
                    <h5>Phím tắt quản trị</h5>
                    <div class="mt-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary me-2">Quản lý danh mục</a>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-success me-2">Quản lý bài viết hệ thống</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>