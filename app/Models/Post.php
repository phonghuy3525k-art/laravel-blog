<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'slug',
    'content',
    'thumbnail', // Thêm dòng này
    'status',
    'published_at',
];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Quan hệ: Bài viết thuộc về một Tác giả (User)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ: Bài viết thuộc về một Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ: Bài viết có nhiều Thẻ (Nhiều - Nhiều)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // Quan hệ: Bài viết có nhiều Bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}