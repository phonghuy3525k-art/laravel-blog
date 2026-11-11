<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    // Hiển thị danh sách toàn bộ bài viết trong hệ thống
    public function index()
    {
        $posts = Post::with('author', 'category')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    // Xóa bài viết bất kỳ bởi Admin
    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Đã xóa bài viết thành công!');
    }
}