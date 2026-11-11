<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lỗi truy cập (403)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center py-5">
    <div class="container">
        <h1 class="display-1 fw-bold text-warning">403</h1>
        <h3>Bạn không có quyền truy cập trang này.</h3>
        <p class="text-muted">Vui lòng quay lại hoặc đăng nhập bằng tài khoản có quyền hạn phù hợp.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Quay về trang chủ</a>
    </div>
</body>
</html>