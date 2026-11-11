<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // 1. Trang chủ: Hiển thị bài viết đã đăng (published), mới nhất, có phân trang và lọc
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->latest('published_at');

        // Tìm kiếm theo từ khóa tiêu đề hoặc nội dung
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Lọc theo tag
        if ($request->has('tag') && $request->tag != '') {
            $tagSlug = $request->tag;
            $query->whereHas('tags', function($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        $posts = $query->paginate(5)->withQueryString();
        $categories = Category::all();
        $tags = Tag::all();

        return view('home', compact('posts', 'categories', 'tags'));
    }

    // 2. Trang chi tiết bài viết theo slug
    public function show($slug)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }
}