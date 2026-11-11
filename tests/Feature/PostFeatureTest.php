<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class PostFeatureTest extends TestCase
{
    use RefreshDatabase;

    // 1. Thất bại: Khách chưa đăng nhập vào trang tạo bài sẽ bị đá về trang login
    public function test_guest_cannot_view_create_post_page()
    {
        $response = $this->get('/my-posts/create');
        $response->assertRedirect('/login');
    }

    // 2. Thành công: User đã đăng nhập vào được trang tạo bài (Status 200)
    public function test_authenticated_user_can_view_create_post_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/my-posts/create');
        $response->assertStatus(200);
    }

    // 3. Thất bại: Đăng bài mà thiếu tiêu đề (Title) sẽ bị báo lỗi validation
    public function test_creating_post_requires_title()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/my-posts', [
            'category_id' => $category->id,
            'content' => 'Nội dung mẫu...',
            'status' => 'published',
            // Thiếu title
        ]);

        $response->assertSessionHasErrors('title');
    }

    // 4. Thành công: Điền đầy đủ dữ liệu hợp lệ để tạo bài viết mới
    public function test_user_can_create_post_with_valid_data()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/my-posts', [
            'title' => 'Bài viết test tự động',
            'category_id' => $category->id,
            'content' => 'Nội dung chi tiết test...',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', ['title' => 'Bài viết test tự động']);
        $response->assertRedirect();
    }

    // 5. Thất bại: Khách chưa đăng nhập không được gửi bình luận
    public function test_guest_cannot_store_comment()
    {
        $post = Post::factory()->create();
        $response = $this->post("/posts/{$post->id}/comments", [
            'content' => 'Bình luận thử nghiệm'
        ]);
        $response->assertRedirect('/login');
    }

    // 6. Thành công: User đã đăng nhập gửi bình luận thành công
    public function test_authenticated_user_can_store_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Bình luận hợp lệ'
        ]);

        $this->assertDatabaseHas('comments', ['content' => 'Bình luận hợp lệ']);
    }

    // 7. Thành công: Chủ nhân có quyền chỉnh sửa bài viết của chính mình
    public function test_user_can_update_their_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/my-posts/{$post->id}", [
            'title' => 'Tiêu đề đã được cập nhật',
            'content' => 'Nội dung cập nhật...',
            'category_id' => $post->category_id,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', ['title' => 'Tiêu đề đã được cập nhật']);
    }

    // 8. Thất bại: Cố tình xóa bài viết của người khác sẽ bị chặn (lỗi 403)
    public function test_user_cannot_delete_others_post()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);

        // Đăng nhập bằng tài khoản khác đi xóa bài của chủ nhân
        $response = $this->actingAs($otherUser)->delete("/my-posts/{$post->id}");

        $response->assertStatus(403); // Bị chặn quyền
    }
}