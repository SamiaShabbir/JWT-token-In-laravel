<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class APIController extends Controller
{
    // methods to handle API responses

    public function sendResponse($data=[], $message, $code = 200)
    {

           $response = [
                    'status'=>'success',
                    'code'=>$code,
                    'message' => $message
                ];

        if(!empty($data))
        {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public function sendError($errorData = [],$message, $code = 400)
    {
        $response = [
            'status'=>'failed',
            'code'=>$code,
            'message' => $message
        ];

        if(!empty($errorData)) {
            $response['error'] = $errorData;
        }

        return response()->json($response, $code);
    }

    public function resourceNotFoundResponse(string $resource)
    {
        $response = [
            'status'=>'failed',
            'code'=>404,
            'error' => "The $resource wasn't found",
        ];

        return response()->json($response, 404);
    }
}
