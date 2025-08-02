<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('posts');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        $sortField = $request->get('sortField', 'name');
        $sortOrder = $request->get('sortOrder', 'asc');
        $query->orderBy($sortField, $sortOrder === '1' ? 'asc' : 'desc');

        $categories = $query->paginate(15);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('message', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $category->load(['posts' => function ($query) {
            $query->with('user')->latest()->limit(10);
        }]);
        
        return Inertia::render('Admin/Categories/Show', [
            'category' => $category,
        ]);
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('message', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return back()->withErrors([
                'message' => 'Cannot delete category that contains posts. Please reassign or delete the posts first.'
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('message', 'Category deleted successfully!');
    }
}