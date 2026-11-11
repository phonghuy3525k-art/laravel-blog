<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bình luận mới</title>
</head>
<body>
    <h2>Xin chào {{ $comment->post->author->name }},</h2>
    <p>Bài viết <strong>{{ $comment->post->title }}</strong> của bạn vừa có một bình luận mới từ người dùng.</p>
    
    <div style="background: #f1f1f1; padding: 10px; margin: 10px 0;">
        <p><strong>Nội dung bình luận:</strong> {{ $comment->content }}</p>
    </div>

    <p>Truy cập vào website để xem chi tiết!</p>
</body>
</html>