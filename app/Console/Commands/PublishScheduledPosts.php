<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish';
    protected $description = 'Tự động chuyển trạng thái các bài viết hẹn giờ đã đến hạn sang Đã đăng';

    public function handle()
    {
        // Lấy các bài viết có status là scheduled và thời gian hẹn <= thời gian hiện tại
        $count = Post::where('status', 'scheduled')
            ->where('published_at', '<=', Carbon::now())
            ->update([
                'status' => 'published',
                'published_at' => Carbon::now() // Cập nhật lại thời gian chính xác lúc đăng nếu cần
            ]);

        $this->info("Đã tự động công bố thành công {$count} bài viết hẹn giờ.");
    }
}