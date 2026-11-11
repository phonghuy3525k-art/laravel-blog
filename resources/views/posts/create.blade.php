<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Viết bài mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h3 class="mb-4">Tạo bài viết mới</h3>
                    
                    <!-- Thêm enctype="multipart/form-data" để hỗ trợ tải lên file ảnh -->
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Thêm ô chọn Ảnh đại diện (Thumbnail) -->
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện (Thumbnail)</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thẻ (Tags)</label>
                            <select name="tags[]" class="form-select" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều thẻ.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="draft">Nháp</option>
                                <option value="published">Đã đăng</option>
                                <option value="scheduled">Hẹn giờ đăng</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thời gian hẹn giờ (nếu có)</label>
                            <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea name="content" rows="6" class="form-control" required>{{ old('content') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Đăng bài</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>