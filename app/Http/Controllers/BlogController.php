<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    /**
     * Display blog homepage with post list
     */
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        $categories = Category::withCount('posts')->get();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     * Display single post
     */
    public function show($slug)
    {
        $post = Post::with(['user', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $relatedPosts = Post::with(['user', 'category'])
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['user', 'category'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        $categories = Category::withCount('posts')->get();

        return Inertia::render('Blog/Category', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}