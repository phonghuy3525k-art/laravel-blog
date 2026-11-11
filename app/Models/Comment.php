<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    // Quan hệ: Bình luận thuộc về một Bài viết
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Quan hệ: Bình luận thuộc về một User (Người viết)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}