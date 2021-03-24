<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return response(['tags' => TagResource::collection($tags), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $tag = Tag::create($data);

        return response(['tag' => new TagResource($tag), 'message' => 'Create new tag successfully !'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);

        if (is_null($tag)) {
            return response(['message' => 'Not found'], 404);
        }

        return response(['tag' => new TagResource($tag), 'message' => 'Retrieved successfully !'], 200);
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'], 422);
        }

        $tag = Tag::find($id);

        $tag->update($data);
        $tag->save();

        return response(['tag' => new TagResource($tag), 'message' => 'Update tag successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        return response(['message' => 'Delete tag successfully'], 200);
    }
}
