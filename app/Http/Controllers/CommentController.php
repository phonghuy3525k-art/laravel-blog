<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // <-- 1. Thêm dòng này
use App\Mail\NewCommentMail;        // <-- 2. Thêm dòng này

class CommentController extends Controller
{
    // Xử lý lưu bình luận mới cho bài viết
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // 3. Gửi email thông báo qua Queue cho tác giả bài viết 
        // (Chỉ gửi nếu người bình luận khác với tác giả và bài viết có thông tin tác giả)
        if ($post->user_id !== Auth::id() && $post->author) {
            Mail::to($post->author->email)->queue(new NewCommentMail($comment));
        }

        return back()->with('success', 'Bình luận của bạn đã được đăng!');
    }
}