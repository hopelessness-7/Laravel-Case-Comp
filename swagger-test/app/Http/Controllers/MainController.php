<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'message' => $message,
            'code' => '200',
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $code, $errorMessages = [])
    {
    	$response = [
            'message' => $error,
            'code' => $code,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
