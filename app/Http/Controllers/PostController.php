<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // 1. Danh sách bài viết của thành viên đang đăng nhập
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with(['category', 'tags'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    // 2. Form tạo bài viết mới
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    // 3. Xử lý lưu bài viết mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : $request->published_at,
        ]);

        // Gắn Tags (Nhiều - Nhiều)
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()->route('posts.index')->with('success', 'Tạo bài viết thành công!');
    }

    // 4. Form sửa bài viết (Yêu cầu: Chặn bảo mật ở server - chỉ tác giả mới được sửa)
    public function edit(Post $post)
    {
        // Chặn bảo mật ở Server (Nhóm B)
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    // 5. Xử lý cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        // Chặn bảo mật ở Server
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048', // Thêm validate ảnh
        ]);

        $thumbnailPath = $post->thumbnail; // Giữ nguyên ảnh cũ nếu không chọn ảnh mới
        if ($request->hasFile('thumbnail')) {
            // (Tùy chọn) Xóa ảnh cũ trong storage nếu muốn dọn rác:
            // if ($post->thumbnail) { \Storage::disk('public')->delete($post->thumbnail); }
            
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath, // Cập nhật đường dẫn ảnh mới
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? ($post->published_at ?? now()) : $request->published_at,
        ]);

        // Đồng bộ Tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    // 6. Xóa bài viết (Đưa vào thùng rác - SoftDeletes)
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $post->delete(); // Soft delete
        return redirect()->route('posts.index')->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    // 7. Trang thùng rác của tôi
    public function trash()
    {
        $posts = Post::onlyTrashed()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('posts.trash', compact('posts'));
    }

    // 8. Khôi phục bài viết từ thùng rác
    public function restore($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $post->restore();

        return redirect()->route('posts.trash')->with('success', 'Khôi phục bài viết thành công!');
    }

    // 9. Xóa vĩnh viễn bài viết
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $post->tags()->detach();
        $post->forceDelete();

        return redirect()->route('posts.trash')->with('success', 'Đã xóa vĩnh viễn bài viết!');
    }
}