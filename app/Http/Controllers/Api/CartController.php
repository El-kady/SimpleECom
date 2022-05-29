<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\CartItems;
use App\Models\Product;
use App\Traits\ApiResponser;
use App\Traits\CartHelpers;
use App\Traits\ProductHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class CartController extends Controller
{
    use ApiResponser, CartHelpers;

    public function index(Request $request)
    {

        $cart = array(
            'total' => 0,
            'shipping_costs' => array(

            ),
            'total_shipping_cost' => 0,
            'items' => array()
        );

        foreach (CartItems::all()->where('user_id', '=', auth()->id()) as $item) {
            $product = $item->product;
            $merchant = $product->merchant;

            $product_price = $this->getPriceAfterVatAttribute($product->price, $merchant->vat_percentage);
            $cart['total'] += $product_price * $item->quantity;

            $i18n_list = array();
            foreach ($item->product->i18n as $i18n) {
                $i18n_list[$i18n['lang']] = array(
                    "product_title" => $i18n->title,
                    "product_price" => $this->getPriceAfterVatAttribute($i18n->price, $merchant->vat_percentage)
                );
            }

            $cart['items'][] = array(
                "product_title" => $product->title,
                "product_price" => $product_price,
                "product_quantity" => $item->quantity,
                'i18n' => $i18n_list
            );

            if ($merchant->shipping_cost > 0 && !isset($cart['shipping_costs'][$merchant->store_name])) {
                $cart['total_shipping_cost'] += $merchant->shipping_cost;
                $cart['shipping_costs'][$merchant->store_name] = $merchant->shipping_cost;
            }
        }

        return $this->sendResponse($cart, "cart items");

    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            // to avoid product duplications
            $item = CartItems::firstOrNew(array(
                'product_id' => $request->product_id,
                'user_id' => auth()->id()
            ));
            $item->quantity = $request->quantity + $item->quantity;
            $item->save();

            $message = 'A product has been successfully added to the cart.';
        } catch (Exception $e) {
            $message = 'Oops! Unable to add a new product.';
        }

        return $this->sendResponse([], $message);
    }


    public function destroy()
    {
        try {
            CartItems::where('user_id',auth()->id())->delete();
        } catch (Exception $e) {
            return $this->sendError('Oops! Unable to delete product.');
        }
    }
}
