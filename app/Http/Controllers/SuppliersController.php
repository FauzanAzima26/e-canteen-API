<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuppliersRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Http\Resources\ResponseResource;
use App\Http\Services\SuppliersServices;

class SuppliersController extends Controller
{

    public function __construct(private SuppliersServices $suppliers)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->paginate) {
            $suppliers = $this->suppliers->getSuppliers(true);
        } else {
            $suppliers = $this->suppliers->getSuppliers();
        }

        if ($suppliers->isEmpty()) {
            return new ResponseResource(true, 'Data Suppliers Not Found', null, [
                'code' => 200
            ], 200);
        }

        return new ResponseResource(
            true,
            'List Data Suppliers',
            $suppliers,
            [
                'code' => 200,
                'total_suppliers' => $suppliers->count()
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuppliersRequest $request)
    {
        $data = $request->validated();

        try {

            $supplier = $this->suppliers->create($data);

            $suppliersResponse = [
                'uuid' => $supplier->uuid,
                'code' => $supplier->code,
                'name' => $supplier->name,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'email' => $supplier->email
            ];

            return new ResponseResource(true, 'Data Suppliers Created', $suppliersResponse, [
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
        $Suppliers = $this->suppliers->getByFirst('uuid', $id);

        if (!$Suppliers) {
            return new ResponseResource(true, 'Data Suppliers with uuid "' . $id . '" Not Found', null, [
                'code' => 404
            ], 404);
        }

        return new ResponseResource(
            true,
            'Data Suppliers Found',
            $Suppliers,
            [
                'code' => 200
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuppliersRequest $request, string $id)
    {
        $data = $request->validated();

        try {
            $getId = $this->suppliers->getByFirst('uuid', $id);

            if (!$getId) {
                return new ResponseResource(true, 'Data Suppliers with uuid "' . $id . '" Not Found', null, [
                    'code' => 404
                ], 404);
            }

            $getId->update($data);

            $suppliersResponse = [
                'uuid' => $getId->uuid,
                'code' => $getId->code,
                'name' => $getId->name,
                'address' => $getId->address,
                'phone' => $getId->phone,
                'email' => $getId->email
            ];

            return new ResponseResource(true, 'Data Suppliers Updated', $suppliersResponse, [
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
        $getId = $this->suppliers->getByFirst('uuid', $id);

        if (!$getId) {
            return new ResponseResource(true, 'Data Suppliers with uuid "' . $id . '" Not Found', null, [
                'code' => 404
            ], 404);
        }

        $getId->delete();

        return new ResponseResource(true, 'Data Suppliers with uuid "' . $id . '" Deleted', null, [
            'code' => 200
        ], 200);
    }
}
