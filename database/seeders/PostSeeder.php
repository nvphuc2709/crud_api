<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::factory(5)->create();
        
        foreach ($posts as $post) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');

            $post->categories()->attach($categories);
            $post->tags()->attach($tags);
        }
    }
}
