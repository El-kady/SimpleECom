<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products), 'Posts retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:5',
            'description' => 'required|min:5',
            'price' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product    = Product::create([
                'title'       => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'merchant_id' => $request->user()->load('merchant')->merchant->id
            ]);
            $success = new ProductResource($product);
            $message = 'Yay! A product has been successfully created.';
        } catch (Exception $e) {
            var_dump($e);exit;
            $success = [];
            $message = 'Oops! Unable to create a new product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) return $this->$this->sendError('Post not found.');

        return $this->sendResponse(new ProductResource($product), 'Post retrieved successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:5',
            'description' => 'required|min:5',
            'price' => 'required'
        ]);

        if ($validator->fails()) return $this->$this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product->title       = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->save();

            $success = new ProductResource($product);
            $message = 'Yay! Post has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->sendResponse([], 'The product has been successfully deleted.');
        } catch (Exception $e) {
            return $this->$this->sendError('Oops! Unable to delete product.');
        }
    }
}
