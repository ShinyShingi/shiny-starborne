<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category']);

        // Apply filters
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Apply sorting
        $sortField = $request->get('sortField', 'created_at');
        $sortOrder = $request->get('sortOrder', 'desc');
        $query->orderBy($sortField, $sortOrder === '1' ? 'asc' : 'desc');

        $posts = $query->paginate(15);

        $categories = Category::all();

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => $request->only(['search', 'status', 'category']),
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Posts/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'image' => 'nullable|string',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->image = $request->image;
        $post->category_id = $request->category_id;
        $post->status = $request->status;
        $post->user_id = auth()->id();
        
        if ($request->status === 'published' && !$request->published_at) {
            $post->published_at = now();
        } elseif ($request->published_at) {
            $post->published_at = $request->published_at;
        }

        $post->save();

        return redirect()->route('admin.posts.index')
            ->with('message', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'category']);
        
        return Inertia::render('Admin/Posts/Show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $post->load(['user', 'category']);

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'image' => 'nullable|string',
        ]);

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->image = $request->image;
        $post->category_id = $request->category_id;
        $post->status = $request->status;

        if ($request->status === 'published' && !$post->published_at && !$request->published_at) {
            $post->published_at = now();
        } elseif ($request->published_at) {
            $post->published_at = $request->published_at;
        }

        $post->save();

        return redirect()->route('admin.posts.index')
            ->with('message', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        try {
            // Delete associated image if exists
            if ($post->image) {
                $storagePath = str_replace('/storage/', '', $post->image);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }
            
            $post->delete();

            return redirect()->route('admin.posts.index')
                ->with('message', 'Post deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'message' => 'Failed to delete post: ' . $e->getMessage()
            ]);
        }
    }
}