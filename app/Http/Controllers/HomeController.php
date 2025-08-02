<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Get latest blog posts
        $latestPosts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Get categories with post count
        $categories = Category::withCount([
            'posts' => function ($query) {
                $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
            }
        ])->orderBy('posts_count', 'desc')
          ->limit(6)
          ->get();

        // Get total published posts count
        $totalPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        return Inertia::render('Home', [
            'latestPosts' => $latestPosts,
            'categories' => $categories,
            'totalPosts' => $totalPosts,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}