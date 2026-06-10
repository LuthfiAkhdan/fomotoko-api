<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try 
        {
            $dataProduct = Product::find($request->product_id);
            if (!$dataProduct) {
                $response = [
                    'success' => false,
                    'message' => 'Product not found.',
                    'data' => []
                ];
                return response()->json($response, 404);
            }
            if ($dataProduct->inventory < $request->quantity) {
                $response = [
                    'success' => false,
                    'message' => 'Insufficient inventory.',
                    'data' => []
                ];
                return response()->json($response, 400);
            }
            if (intval($request->quantity) <= 0) {
                $response = [
                    'success' => false,
                    'message' => 'Quantity cannot be negative or zero.',
                    'data' => []
                ];
                return response()->json($response, 400);
            }
            $data = Order::where('buyer_name', $request->buyer_name)->whereNull('deleted_at')->first();
            if (!$data) {
                $data = new Order();
            }
            $data->buyer_name = $request->buyer_name;
            $data->save();

            if ($data) {
                if ($dataProduct->flash_sale_price !== null) {
                    $price = $dataProduct->flash_sale_price;
                } else {
                    $price = $dataProduct->price;
                }
                $dataItem              = new OrderItem();
                $dataItem->order_id    = $data->id;
                $dataItem->product_id  = $request->product_id;
                $dataItem->quantity    = $request->quantity;
                $dataItem->price       = $price * $request->quantity;

                $dataProduct->inventory -= $request->quantity;
                $dataProduct->save();

                $dataItem->save();

                $response = [
                    'success' => true,
                    'message' => 'Order created successfully.',
                    'data' => $data
                ];
                $status = 200;
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to create order.',
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
        $data = Order::with('order_items.product:id,name')
                ->withSum('order_items as total_price', 'price')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();
        
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

    public function destroy_item($id, $item_id)
    {
        $orderItem = OrderItem::find($item_id);
        if (!$orderItem || $orderItem->order_id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Order item not found'
            ], 404);
        }
        $product = Product::find($orderItem->product_id);
        if ($product) {
            $product->inventory += $orderItem->quantity;
            $product->save();
        }
        $orderItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order item deleted successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        foreach ($order->order_items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->inventory += $item->quantity;
                $product->save();
            }
        }
        $order->order_items()->delete();

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ], 200);
    }
}
