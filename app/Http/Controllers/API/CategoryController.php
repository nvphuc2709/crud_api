<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response(['categories' => CategoryResource::collection($categories), 'message' => 'Retrieved successfully'], 200);
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

        $category = Category::create($data);

        return response(['category' => new CategoryResource($category), 'message' => 'Create new category successfully !'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response(['message' => 'Not found'], 404);
        }

        return response(['category' => new CategoryResource($category), 'message' => 'Retrieved successfully !'], 200);
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
            'status' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'], 422);
        }

        $category = Category::find($id);
//        $category->status = $data['status'];

        $category->update($data);
        $category->save();

        return response(['category' => new CategoryResource($category), 'message' => 'Update category successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response(['message' => 'Delete category successfully'], 200);
    }

    /**
     * Searching data with 'name' and 'status' param in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $categoryName = $request->input('name');
        $categoryStatus = $request->input('status');

        if (!is_null($categoryName) && !is_null($categoryStatus)) {
            $category = Category::where([
                ['name', $categoryName],
                ['status', $categoryStatus],
            ])->first();
        } elseif (!is_null($categoryName)) {
            $category = Category::where('name', $categoryName)->first();
        } elseif (!is_null($categoryStatus)) {
            $category = Category::where('status', $categoryStatus)->first();
        } else {
            return redirect()->action([CategoryController::class, 'index']);
        }

        if (is_null($category)) {
            return response(['message' => 'Not found'], 404);
        }

        return response(['category' => new CategoryResource($category), 'message' => 'Retrieved successfully !'], 200);
    }
}
