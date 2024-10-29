<?php
declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function successRes(array $data = null, string $message = "success"): JsonResponse
    {
        if ($data)
            return response()->json([
                "data" => $data,
                "status" => true,
                "message" => $message
            ]);
        return response()->json([
            "status" => true,
            "message" => $message
        ]);
    }
    public function paginateRes($data = null): JsonResponse
    {
        return response()->json($data);
    }

    public function errorRes(string $message = "error", int $bodyCode = 423, int $code = 423): JsonResponse
    {
        return response()->json([
            "status" => false,
            "code" => $bodyCode,
            "message" => $message
        ], $code);
    }
}
