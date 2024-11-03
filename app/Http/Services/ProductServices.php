<?php

namespace App\Http\Services;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class ProductServices
{
    public function getProduct($paginate = false)
    {
        if ($paginate) {
            $Product = Product::with('category:id,name', 'supplier:id,name')->when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })->select('id', 'name', 'slug', 'price', 'stock', 'description', 'image', 'category_id', 'supplier_id')->latest()->paginate(10);
        } else {
            $Product = Product::with('category:id,name', 'supplier:id,name')->when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })->get();
        }

        return $Product;
    }

    public function getByFirst($coloumn, $value, $Relation = false)
    {
        if ($Relation) {
            $Product = Product::where($coloumn, $value)->with('category:id,name', 'supplier:id,name')->first();
            return $Product;
        } else {
            $Product = Product::where($coloumn, $value)->first();
            return $Product;
        }
    }

    public function create($data)
    {
        $data['slug'] = Str::slug($data['name']);
        
        $Product = Product::create($data);
        return $Product;
    }

    // public function update($data, $id)
    // {
    //     $data['slug'] = Str::slug($data['name']);

    //     $Product = Product::where('uuid', $id)->first();

    //     $Product->update($data);

    //     return $Product;
    // }
}