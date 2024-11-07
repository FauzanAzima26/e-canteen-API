<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Services\ProductServices;
use App\Http\Resources\ResponseResource;
use App\Http\Services\FileServices;

class ProductController extends Controller
{
    public function __construct(
        private ProductServices $productServices,
        private FileServices $fileServices
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productServices->getProduct(true);

        $products->getcollection()->transform(function($product) {
            $product->price = 'Rp.'.number_format($product->price, 0, ',', '.');
            return $product;
        });

        if ($products->isEmpty()) {
            return new ResponseResource(true, 'Data Product Not Found', null, [
                'code' => 200
            ], 200);
        }

        return new ResponseResource(true,'List Data Product',$products,
  [
                'code' => 200,
                'total_product' => $products->count()
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        try{

            $uploadImage = $this->fileServices->upload($data['image'], 'images');
            $data['image'] = $uploadImage;

            $product = $this->productServices->create($data);

            $productResponse = [
                'uuid' => $product->uuid,
                'category_id' => $product->category_id,
                'supplier_id' => $product->supplier_id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'stock' => $product->stock,
                'description' => $product->description,
            ];

            $productMeta = $this->productServices->getByFirst('uuid', $product->uuid, true);

            return new ResponseResource(true, 'Product Created', $productResponse, 
  [
                'code' => 201,
                'category_name' => $productMeta->category->name,
                'supplier_name' => $productMeta->supplier->name,
                'image_url' => url(asset('storage/' . $productMeta->image))
            ], 201);   

        }catch(\Exception $e){
            return new ResponseResource(false, $e->getMessage(), null, 
            [
                'code' => 500
            ], 500);
        }
    }

    public function show(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        
    }

    public function destroy(Product $product)
    {
        //
    }
}
