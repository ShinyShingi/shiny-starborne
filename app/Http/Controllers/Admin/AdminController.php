<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
        ];

        // Get recent posts
        $recent_posts = Post::with(['user', 'category'])
            ->latest()
            ->limit(5)
            ->get();

        // Get popular categories
        $popular_categories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        // Monthly post statistics
        $monthly_stats = Post::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recent_posts' => $recent_posts,
            'popular_categories' => $popular_categories,
            'monthly_stats' => $monthly_stats,
        ]);
    }
}