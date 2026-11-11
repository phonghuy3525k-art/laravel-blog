<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $postCount = Post::count();
        $userCount = User::count();
        $commentCount = Comment::count();
        $categoryCount = Category::count();

        return view('admin.dashboard', compact('postCount', 'userCount', 'commentCount', 'categoryCount'));
    }
}