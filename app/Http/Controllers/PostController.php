<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- 1. Thêm dòng khai báo Log ở đầu file
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

        // 2. Ghi log sự kiện tạo bài viết thành công
        Log::info('Bài viết mới đã được tạo thành công. ID: ' . $post->id . ' bởi User ID: ' . Auth::id());

        return redirect()->route('posts.index')->with('success', 'Tạo bài viết thành công!');
    }

    // 4. Form sửa bài viết (Yêu cầu: Chặn bảo mật ở server - chỉ tác giả mới được sửa)
    public function edit(Post $post)
    {
        // Chặn bảo mật ở Server (Nhóm B)
        if ($post->user_id !== Auth::id()) {
            // Ghi log cảnh báo cố tình truy cập trái phép
            Log::warning('Cảnh báo bảo mật: User ID ' . Auth::id() . ' cố gắng chỉnh sửa bài viết không thuộc sở hữu (Post ID: ' . $post->id . ')');
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
            Log::warning('Cảnh báo bảo mật: User ID ' . Auth::id() . ' cố gắng cập nhật bài viết không thuộc sở hữu (Post ID: ' . $post->id . ')');
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $thumbnailPath = $post->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? ($post->published_at ?? now()) : $request->published_at,
        ]);

        // Đồng bộ Tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        // Ghi log cập nhật thành công
        Log::info('Bài viết đã được cập nhật thành công. ID: ' . $post->id . ' bởi User ID: ' . Auth::id());

        return redirect()->route('posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    // 6. Xóa bài viết (Đưa vào thùng rác - SoftDeletes)
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            Log::warning('Cảnh báo bảo mật: User ID ' . Auth::id() . ' cố gắng xóa bài viết không thuộc sở hữu (Post ID: ' . $post->id . ')');
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $post->delete(); // Soft delete

        // Ghi log chuyển vào thùng rác
        Log::info('Bài viết đã được chuyển vào thùng rác (Soft Delete). ID: ' . $post->id . ' bởi User ID: ' . Auth::id());

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

        // Ghi log khôi phục
        Log::info('Bài viết đã được khôi phục từ thùng rác. ID: ' . $post->id . ' bởi User ID: ' . Auth::id());

        return redirect()->route('posts.trash')->with('success', 'Khôi phục bài viết thành công!');
    }

    // 9. Xóa vĩnh viễn bài viết
    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $postId = $post->id;
        $post->tags()->detach();
        $post->forceDelete();

        // Ghi log xóa vĩnh viễn
        Log::warning('Bài viết đã bị xóa vĩnh viễn (Force Delete). ID: ' . $postId . ' bởi User ID: ' . Auth::id());

        return redirect()->route('posts.trash')->with('success', 'Đã xóa vĩnh viễn bài viết!');
    }
}