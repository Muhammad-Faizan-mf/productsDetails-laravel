<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $filePath = 'productsDetails.json';

    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_name' => 'required|string',
            'quantity_in_stock' => 'required|integer|min:0',
            'price_per_item' => 'required|numeric|min:1',
        ]);

        $total = $request->quantity_in_stock * $request->price_per_item;

        $newProduct = [
            'product_name' => $request->product_name,
            'quantity_in_stock' => (int) $request->quantity_in_stock,
            'price_per_item' => (float) $request->price_per_item,
            'datetime_submitted' => now()->toDateTimeString(),
            'total_value' => $total,
        ];
        $products = $this->getProducts();
        $products[] = $newProduct;
        Storage::put($this->filePath, json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'products' => $products]);
    }

    public function getJsonData()
    {
        return response()->json($this->getProducts());
    }

    private function getProducts()
    {
        if (Storage::exists($this->filePath)) {
            return json_decode(Storage::get($this->filePath), true);
        }
        else{
        return [];
        }
    }
}
