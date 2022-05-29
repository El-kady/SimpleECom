<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    use ApiResponser;

    public function save(Request $request){

        $validator = Validator::make($request->all(), [
            'store_name'    => 'required'
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors(), 422);

        try {
            Merchant::where("user_id", $request->user()->id)
                ->update([
                    "store_name" => $request->store_name,
                    "shipping_cost" => $request->shipping_cost,
                    "vat_included" => $request->vat_included,
                    "vat_percentage" => $request->vat_percentage
                ]);

            return $this->sendResponse([], 'A merchant has been successfully saved.');

        } catch (Exception $e) {
            return $this->sendError('error during save the record', ['error' => 'error'], 401);
        }
    }
}
