<?php
namespace App\Helpers;

use Illuminate\Http\JsonResponse;

// class ApiResponse {
 
//     private static function response(bool $status, string $message, $data, int $statusCode, bool $isError = false): JsonResponse {
//         return response()->json([
//             'status' => $status,
//             'message' => $message,
//             $isError ? 'errors' : 'data' => $data,  // Conditionally set 'errors' or 'data'
//             'code' => $statusCode,
//         ], $statusCode);
        
//     }

//     public static function success($data, string $message = "Success", int $statusCode = 200 ): JsonResponse {
//         return self::response(true, $message, $data, $statusCode);
//     }

//     public static function error($data, string $message = "Error!", int $statusCode = 500): JsonResponse {
//         return self::response(false, $message, $data, $statusCode, true);
//     }
// }

class ApiResponse {

    private static function response(
        bool $status,
        string $message,
        $data,
        int $statusCode,
        bool $isError = false,
        $token = null
    ): JsonResponse {

        $response = [
            'status' => $status,
            'message' => $message,
            $isError ? 'errors' : 'data' => $data,
            'code' => $statusCode,
        ];

        if ($token) {
            $response['token'] = $token;
        }

        return response()->json($response, $statusCode);
    }

    public static function success(
        $data,
        string $message = "Success",
        int $statusCode = 200,
        $token = null
    ): JsonResponse {
        return self::response(true, $message, $data, $statusCode, false, $token);
    }

    public static function error(
        $data,
        string $message = "Error!",
        int $statusCode = 500
    ): JsonResponse {
        return self::response(false, $message, $data, $statusCode, true);
    }
}