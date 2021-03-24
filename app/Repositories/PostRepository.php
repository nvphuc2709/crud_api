<?php


namespace App\Repositories;


use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Arr;


class PostRepository implements PostRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getAll(array $data = [])
    {
        if(sizeof($data) == 0)
            return Post::with(['user', 'categories', 'tags'])->get();

        $categories = Arr::has($data, 'categories') ? $data['categories'] : null;
        $status = Arr::has($data, 'status') ? $data['status'] : null;
        $highlight = Arr::has($data, 'highlight') ? $data['highlight'] : null;
        $postName = Arr::has($data, 'name') ? $data['name'] : null;
        $tagName = Arr::has($data, 'tag') ? $data['tag'] : null;

        $posts = Post::with(['user', 'categories', 'tags'])
            ->when($categories, function ($query) use ($categories){
                $query->whereHas('categories', function ($query) use ($categories) {
                    $query->where('category_id', eval("return $categories;"));
                });
            })
            ->when($status, function ($query, $status){
                $query->where('status', $status);
            })
            ->when($highlight, function ($query, $highlight){
                $query->where('highlight',eval("return $highlight;"));
            })
            ->when($postName, function ($query, $postName){
                $query->where('name', 'like', $postName.'%');
            })
            ->when($tagName, function ($query) use ($tagName){
                $query->whereHas('tags', function ($query) use ($tagName) {
                    $query->where('name', 'like', $tagName.'%');
                });
            })
            ->get();
        return sizeof($posts) == 0 ? [] : $posts;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        return Post::find($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data = [])
    {
        $post = Post::create($data);

        $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
        $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');

        $post->categories()->attach($categories);
        $post->tags()->attach($tags);

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function update($id, array $data = [])
    {
        return Post::find($id)->update($data)->save();
    }

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();

        return true;
    }
}
