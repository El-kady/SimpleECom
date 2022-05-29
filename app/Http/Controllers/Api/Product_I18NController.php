<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product_I18NResource;
use App\Models\Product;
use App\Models\Product_I18N;
use App\Traits\ProductHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class Product_I18NController extends Controller
{
    use ProductHelpers;

    public function index()
    {
        $products = Product_I18N::all();
        return $this->sendResponse(Product_I18NResource::collection($products), 'Products internationalization retrieved successfully.');
    }

    public function store($product_id,Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lang'       => 'required',
            'title'       => 'required|min:5',
            'description' => 'required|min:5',
            'price' => 'required',
            'currency' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product    = Product_I18N::create([
                'lang'       => $request->lang,
                'title'       => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'currency' => $request->currency,
                'product_id' => $product_id
            ]);
            $success = new Product_I18NResource($product);
            $message = 'A product internationalization has been successfully created.';
        } catch (Exception $e) {
            var_dump($e);exit;
            $success = [];
            $message = 'Oops! Unable to create a new product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function show($id)
    {
        $product = Product_I18N::find($id);

        if (is_null($product)) return $this->$this->sendError('Product not found.');

        return $this->sendResponse(new Product_I18NResource($product), 'Product internationalization retrieved successfully.');
    }

    public function update(Request $request, Product_I18N $product)
    {
        $validator = Validator::make($request->all(), [
            'lang'       => $request->lang,
            'title'       => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency
        ]);

        if ($validator->fails()) return $this->$this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            $product->lang       = $request->lang;
            $product->title       = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->currency = $request->currency;
            $product->save();

            $success = new Product_I18NResource($product);
            $message = 'Product internationalization has been successfully updated.';
        } catch (Exception $e) {
            $success = [];
            $message = 'Oops, Failed to update the product.';
        }

        return $this->sendResponse($success, $message);
    }


    public function destroy(Product_I18N $product)
    {
        try {
            $product->delete();
            return $this->sendResponse([], 'The product has been successfully deleted.');
        } catch (Exception $e) {
            return $this->$this->sendError('Oops! Unable to delete product.');
        }
    }
}
