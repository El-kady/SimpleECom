<?php

namespace App\Traits;
use Carbon\Carbon;

trait ApiResponser
{

    function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        !empty($errorMessages) ? $response['data'] = $errorMessages : null;

        return response()->json($response, $code);
    }

}
