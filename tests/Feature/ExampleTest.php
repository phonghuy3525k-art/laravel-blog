<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- Thêm dòng này
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // <-- Thêm dòng này để tự động tạo bảng khi test

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}