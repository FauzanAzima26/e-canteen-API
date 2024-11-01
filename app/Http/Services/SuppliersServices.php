<?php

namespace App\Http\Services;

use App\Models\Suppliers;
use Illuminate\Support\Str;

class SuppliersServices
{
    public function getSuppliers($paginate = false)
    {
        if ($paginate) {
            $Suppliers = Suppliers::when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })->latest()->paginate(10);
        } else {
            $Suppliers = Suppliers::when(request()->search, function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%');
            })->get();
        }

        return $Suppliers;
    }

    public function getByFirst($coloumn, $value)
    {
        $Suppliers = Suppliers::where($coloumn, $value)->first();
        return $Suppliers;
    }

    public function create($data)
    {
        $data['slug'] = Str::slug($data['name']);
        
        $Suppliers = Suppliers::create($data);
        return $Suppliers;
    }

    // public function update($data, $id)
    // {
    //     $data['slug'] = Str::slug($data['name']);

    //     $Suppliers = Suppliers::where('uuid', $id)->first();

    //     $Suppliers->update($data);

    //     return $Suppliers;
    // }
}