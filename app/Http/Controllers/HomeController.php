<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    const jsonFile = 'products.json';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home');
    }

    public function fetch()
    {
        $products = $this->getProductList();
        usort($products, function($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });
        return json_encode($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'qty' => ['required', 'integer'],
            'price' => ['required', 'integer'],
        ]);

        $newProduct = [
            'id' => (string) Str::uuid(),
            'name' => $data['name'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $products = $this->getProductList();
        $products[] = $newProduct;

        Storage::disk('public')->put(self::jsonFile, json_encode($products));
        return json_encode(['status' => 'success', 'message' => 'Successfully submitted.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'productId' => ['required', 'string'],
            'editProductName' => ['required', 'string'],
            'editProductQty' => ['required', 'integer'],
            'editProductPrice' => ['required', 'integer'],
        ]);

        $updatedProduct = [
            'id' => $data['productId'],
            'name' => $data['editProductName'],
            'qty' => $data['editProductQty'],
            'price' => $data['editProductPrice']
        ];

        $targettedProductIndex = null;
        $products = $this->getProductList();
        foreach ($products as $index => $product) {
            if ($product['id'] == $data['productId']) {
                $updatedProduct['created_at'] = $product['created_at'];
                $targettedProductIndex = $index;
                break;
            }
        }

        if ($targettedProductIndex) {
            unset($products[$targettedProductIndex]);
            $products[] = $updatedProduct;
            
            Storage::disk('public')->put(self::jsonFile, json_encode($products));
            return json_encode(['status' => 'success', 'message' => 'Successfully edited.']);
        }

        return json_encode(['status' => 'error', 'message' => 'Product not found.']);
    }

    /**
     * Delete the specified resource in storage.
     */
    public function delete(Request $request)
    {
        $data = $request->validate([
            'productId' => ['required', 'string'],
        ]);

        $targettedProductIndex = null;
        $products = $this->getProductList();
        foreach ($products as $index => $product) {
            if ($product['id'] == $data['productId']) {
                $targettedProductIndex = $index;
                break;
            }
        }

        if ($targettedProductIndex) {
            unset($products[$targettedProductIndex]);
            
            Storage::disk('public')->put(self::jsonFile, json_encode($products));
            return json_encode(['status' => 'success', 'message' => 'Successfully deleted.']);
        }

        return json_encode(['status' => 'error', 'message' => 'Product not found.']);
    }

    private function getProductList()
    {
        $products = json_decode(Storage::disk('public')->get(self::jsonFile), true);

        if (empty($products)) {
            $products = [];
        }

        return $products;
    }
}
