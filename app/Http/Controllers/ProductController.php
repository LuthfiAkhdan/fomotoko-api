<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::whereNull('deleted_at')->get();
        
        if ($data->isEmpty()) {
            $response = [
                'success' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
            $status = 404;
        } else {
            $response = [
                'success' => true,
                'message' => 'Data Retrieved',
                'data' => $data
            ];
            $status = 200;
        }

        return response()->json($response, $status);
    }

    public function store(Request $request)
    {
        try 
        {
            if (intval($request->inventory) <= 0) {
                $response = [
                    'success' => false,
                    'message' => 'Inventory cannot be negative or zero.',
                    'data' => []
                ];
                return response()->json($response, 400);
            }
            $data                   = new Product();
            $data->name             = $request->name;
            $data->price            = floatval($request->price);
            $data->flash_sale_price = $request->flash_sale_price !== null ? floatval($request->flash_sale_price) : null;
            $data->inventory        = intval($request->inventory);
            $data->save();

            if ($data) {
                $response = [
                    'success' => true,
                    'message' => 'Product Name ' . $request->name . ' added successfully.',
                    'data' => $data
                ];
                $status = 200;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to add new product.',
                    'data' => []
                ];
                $status = 500;
            }
            return response()->json($response, $status);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage() . ' on file ' . $e->getFile() . ' on line number ' . $e->getLine(),
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }

    public function find($id)
    {
        $data = Product::where('id', $id)->whereNull('deleted_at')->first();
        
        if (!$data) {
            $response = [
                'success' => false,
                'message' => 'No Data Found',
                'data' => []
            ];
            $status = 404;
        } else {
            $response = [
                'success' => true,
                'message' => 'Data Retrieved',
                'data' => $data
            ];
            $status = 200;
        }

        return response()->json($response, $status);
    }

    public function update(Request $request, $id)
    {
        try 
        {
            $data                   = Product::where('id', $id)->whereNull('deleted_at')->first();
            $data->name             = $request->name !== null ? $request->name : $data->name;
            $data->price            = $request->price !== null ? floatval($request->price) : $data->price;
            $data->flash_sale_price = $request->flash_sale_price !== null ? floatval($request->flash_sale_price) : $data->flash_sale_price;
            $data->inventory        = $request->inventory !== null ? intval($request->inventory) : $data->inventory;
            $data->save();

            if ($data) {
                $response = [
                    'success' => true,
                    'message' => 'Product Name ' . $request->name . ' updated successfully.',
                    'data' => $data
                ];
                $status = 200;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to update product.',
                    'data' => []
                ];
                $status = 500;
            }
            return response()->json($response, $status);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage() . ' on file ' . $e->getFile() . ' on line number ' . $e->getLine(),
                'data' => []
            ];
            return response()->json($response, 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
