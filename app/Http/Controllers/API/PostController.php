<?php

namespace App\Http\Controllers\API;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    protected $post;

    /**
     * PostController constructor.
     *
     * @param PostRepositoryInterface $post
     */
    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arguments = $request->all();

        $posts = $this->post->getAll($arguments);
        return sizeof($posts) == 0 ?
            response(['message' => 'Not found'], 404) :
            response()->json(CollectionHelper::paginate(collect(PostResource::collection($posts)), 2));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arguments = $request->all();

        $validator = Validator::make($arguments, [
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'content' => ['nullable'],
            'status' => Rule::in(['draft', 'publish']),
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()],422);
        }

        $post = $this->post->create($arguments);

        return response(['data' => new PostResource($post), 'message' => 'Create new post successfully !'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $post = $this->post->get($id);

        if (is_null($post)) {
            return response(['message' => 'Not found'], 404);
        }

        return response(['data' => new PostResource($post), 'message' => 'Retrieved successfully !'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $arguments = $request->all();

        $validator = Validator::make($arguments, [
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'content' => ['nullable'],
            'status' => Rule::in(['draft', 'publish']),
            'highlight' => ['nullable','boolean'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()],422);
        }

        $post = $this->post->update($id, $arguments);
        return response(['data' => new PostResource($post), 'message' => 'Update post successfully !'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->post->delete($id);

        return response(['message' => 'Delete post successfully !'],200);
    }
}
