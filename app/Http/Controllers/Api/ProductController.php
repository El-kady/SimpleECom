<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ProductHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    use ProductHelpers;

    public function index(Request $request)
    {
        $merchant_id = $request->user()->load('merchant')->merchant->id;
        $products = Product::all()->where('merchant_id', '=', $merchant_id);
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'price' => 'required',
            'currency' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'currency' => $request->currency,
                'merchant_id' => $request->user()->load('merchant')->merchant->id
            ]);
            $success = new ProductResource($product);
            $message = 'A product has been successfully created.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops! Unable to create a new product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product) || !$product->isMine()) return $this->sendError('Product not found or not yours.');

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    public function update(Request $request, Product $product)
    {

        if (!$product->isMine()) return $this
            ->sendError('Product not found or not yours.');

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'description' => 'required|min:5',
            'price' => 'required',
            'currency' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->currency = $request->currency;
            $product->save();

            $success = new ProductResource($product);
            $message = 'Yay! Product has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function destroy(Product $product)
    {
        try {
            if (is_null($product) || !$product->isMine()) return $this->sendError('Product not found or not yours.');
            $product->delete();
            return $this->sendResponse([], 'The product has been successfully deleted.');
        } catch (Exception $e) {
            return $this->sendError('Oops! Unable to delete product.');
        }
    }
}
