<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản Admin mặc định
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@blog.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Tạo vài tài khoản Member mẫu
        $members = User::factory(5)->create();

        // 3. Tạo danh mục và Thẻ
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(10)->create();

        // 4. Tạo bài viết và gắn Thẻ ngẫu nhiên (Many-to-Many)
        Post::factory(20)->create()->each(function ($post) use ($tags) {
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
        });

        // 5. Tạo bình luận mẫu
        Comment::factory(40)->create();
    }
}