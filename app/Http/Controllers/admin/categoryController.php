<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\category\createCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class categoryController extends Controller
{
    public function create(createCategoryRequest $request)
    {
        try {
            if (!empty($request->parent_id))
            {
                if (Category::find($request->parent_id) == null)
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'main category no\'t exists'
                    ],  200);
                }

            }

            Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ok'
            ],  201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function mainCategories()
    {
        try {
            $categories = Category::where('parent_id', null)->get();

            return response()->json([
                'success' => true,
                'data' =>  $categories
            ],  201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function subCategories($parent_id)
    {
        try {
            $categories = Category::where('parent_id', $parent_id)->get();

            return response()->json([
                'success' => true,
                'data' =>  $categories
            ],  201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
