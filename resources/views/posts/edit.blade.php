<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h3 class="mb-4">Chỉnh sửa bài viết</h3>
                    
                    <!-- Thêm enctype="multipart/form-data" để hỗ trợ upload file ảnh -->
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $post->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Hiển thị ảnh cũ và ô chọn Ảnh đại diện (Thumbnail) mới -->
                        <div class="mb-3">
                            <label class="form-label">Ảnh đại diện (Thumbnail)</label>
                            @if($post->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" width="150" class="rounded shadow-sm">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            <small class="text-muted">Chọn ảnh mới nếu muốn thay thế ảnh hiện tại.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thẻ (Tags)</label>
                            <select name="tags[]" class="form-select" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều thẻ.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Nháp</option>
                                <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Đã đăng</option>
                                <option value="scheduled" {{ $post->status == 'scheduled' ? 'selected' : '' }}>Hẹn giờ đăng</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea name="content" rows="6" class="form-control" required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>