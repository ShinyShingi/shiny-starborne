<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);
        }

        $posts = [
            [
                'title' => 'Getting Started with Laravel 12',
                'content' => 'Laravel 12 brings exciting new features and improvements. In this post, we\'ll explore the latest additions to the framework and how they can enhance your development workflow.',
                'category' => 'Technology',
                'status' => 'published',
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Top 10 Travel Destinations for 2025',
                'content' => 'Discover the most breathtaking destinations to visit this year. From tropical beaches to mountain retreats, we\'ve compiled the ultimate travel bucket list.',
                'category' => 'Travel',
                'status' => 'published',
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'Healthy Meal Prep Ideas',
                'content' => 'Save time and eat healthy with these meal prep ideas. Learn how to prepare nutritious meals for the entire week in just a few hours.',
                'category' => 'Food & Recipes',
                'status' => 'published',
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'The Future of AI in Business',
                'content' => 'Artificial Intelligence is transforming the business landscape. Explore how AI technologies are reshaping industries and creating new opportunities.',
                'category' => 'Business',
                'status' => 'published',
                'published_at' => now()->subDay()
            ],
            [
                'title' => 'Building Scalable Vue Applications',
                'content' => 'Learn best practices for building large-scale Vue.js applications. This guide covers architecture patterns, state management, and performance optimization.',
                'category' => 'Technology',
                'status' => 'published',
                'published_at' => now()
            ],
            [
                'title' => 'Morning Workout Routines',
                'content' => 'Start your day right with these effective morning workout routines. No equipment needed - just 20 minutes to boost your energy and fitness.',
                'category' => 'Health & Fitness',
                'status' => 'draft',
                'published_at' => null
            ],
            [
                'title' => 'Understanding Quantum Computing',
                'content' => 'Quantum computing is no longer science fiction. This article breaks down the basics of quantum mechanics and its applications in computing.',
                'category' => 'Science',
                'status' => 'draft',
                'published_at' => null
            ],
            [
                'title' => 'The Rise of Remote Work Culture',
                'content' => 'Remote work has become the new normal. Discover strategies for building a successful remote work culture and maintaining team productivity.',
                'category' => 'Business',
                'status' => 'published',
                'published_at' => now()->subDays(7)
            ],
            [
                'title' => 'Essential Photography Tips for Beginners',
                'content' => 'Master the basics of photography with these essential tips. From composition to lighting, learn how to capture stunning images.',
                'category' => 'Lifestyle',
                'status' => 'published',
                'published_at' => now()->subDays(4)
            ],
            [
                'title' => 'Latest Developments in Space Exploration',
                'content' => 'Space exploration continues to advance at an incredible pace. From Mars missions to private space travel, explore the latest achievements.',
                'category' => 'Science',
                'status' => 'published',
                'published_at' => now()->subDays(6)
            ]
        ];

        foreach ($posts as $postData) {
            $category = Category::where('name', $postData['category'])->first();
            
            Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => $postData['content'],
                'status' => $postData['status'],
                'published_at' => $postData['published_at'],
                'user_id' => $user->id,
                'category_id' => $category->id
            ]);
        }
    }
}
