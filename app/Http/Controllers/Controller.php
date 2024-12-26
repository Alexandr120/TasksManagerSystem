<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param string $status
     * @param string $message
     * @param int $code
     * @param array $otherData
     * @return JsonResponse
     */
    public function sendResponse(string $status, string $message, int $code, array $otherData=[]): JsonResponse
    {
        $responseData = array_merge([
            'status' => $status,
            'message' => $message
        ], $otherData);

        return response()->json($responseData, $code);
    }
}
