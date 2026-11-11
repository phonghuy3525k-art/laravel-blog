<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Post;

class PostUnitTest extends TestCase
{
    // Unit Test 1: Kiểm tra gán thuộc tính status của Model Post
    public function test_post_has_default_status_or_attributes()
    {
        $post = new Post([
            'title' => 'Tiêu đề mẫu',
            'status' => 'draft'
        ]);

        $this->assertEquals('draft', $post->status);
    }

    // Unit Test 2: Kiểm tra gán và lấy tiêu đề của Model Post
    public function test_post_title_can_be_accessed()
    {
        $post = new Post(['title' => 'Học Laravel cùng AI']);
        $this->assertEquals('Học Laravel cùng AI', $post->title);
    }
}