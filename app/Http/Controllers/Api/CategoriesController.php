<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResponseResource;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categories::when(request()->search, function ($query) {
            $query->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        if ($categories->isEmpty()) {
            return new ResponseResource(true, 'Data Categories Not Found', null, [
                'code' => 200
            ], 200);
        }

        return new ResponseResource(
            true,
            'List Data Categories',
            $categories,
            [
                'code' => 200,
                'total_categories' => $categories->count()
            ], 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);

        try {
            $data['slug'] = Str::slug($data['name']);
            $categories = Categories::create($data);

            $categoriesResponse = [
                'uuid' => $categories->uuid,
                'name' => $categories->name, 
                'slug' => $categories->slug
            ];

            return new ResponseResource(true, 'Data Categories Created', $categoriesResponse, [
                'code' => 201
            ], 201);
        } catch (\Exception $e) {
            return new ResponseResource(false, $e->getMessage(), null, [
                'code' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Categories::where('uuid', $id)->first();

        if (!$categories) {
            return new ResponseResource(true, 'Category with uuid' . $id . ' not found', null, [
                'code' => 404
            ], 404);
        }

        return new ResponseResource(true,'Category with uuid ' . $id . ' found',$categories,
  [
                'code' => 200,
            ], 200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:3|unique:categories,name,' . $id . ',uuid',
        ]);

        try {

            $categories = Categories::where('uuid', $id)->first();

            if (!$categories) {
                return new ResponseResource(false, 'Category with uuid' . $id . ' not found', null, [
                    'code' => 404
                ], 404);
            }
            
            $data['slug'] = Str::slug($data['name']);

            $categories->update($data);

            $categoriesResponse = [
                'uuid' => $categories->uuid,
                'name' => $categories->name, 
                'slug' => $categories->slug
            ];

            return new ResponseResource(true, 'Data Categories Updated', $categoriesResponse, [
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return new ResponseResource(false, $e->getMessage(), null, [
                'code' => 500
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $categories = Categories::where('uuid', $id)->first();

            if (!$categories) {
                return new ResponseResource(false, 'Category with uuid' . $id . ' not found', null, [
                    'code' => 404
                ], 404);
            }            

            $categories->delete();

            return new ResponseResource(true, 'Data Categories Deleted', null, [
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return new ResponseResource(false, $e->getMessage(), null, [
                'code' => 500
            ], 500);
        }
    }
}
