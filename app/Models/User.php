<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Phân quyền: 'admin' hoặc 'member'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

   protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];

    // Quan hệ: Một user có nhiều bài viết
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Quan hệ: Một user có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Kiểm tra quyền nhanh
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}