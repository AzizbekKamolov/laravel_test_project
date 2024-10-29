<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProviderModel;
use App\Models\StorageModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StorageService
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function buyingProducts(Request $request): JsonResponse
    {
        $batch = $this->getBatch();
        $result = [];
        foreach ($request->get('batches') as $item => $value) {
            $product = $this->getProduct($value['product_id']);
            $provider = $this->getProviderByCategoryId($product->category_id, $value['provider_id']);
            if (!$provider) {
                return response()->json([
                    "status" => "false",
                    "message" => "The batches.$item.provider_id field is invalid",
                    "errors" => [
                        "batches.provider_id" => [
                            "The batches.$item.provider_id field is invalid"
                        ]
                    ],
                    "code" => 422
                ], 422);
            }
            $result[] = [
                "provider_id" => $value['provider_id'],
                "product_id" => $value['product_id'],
                "quantity" => $value['quantity'],
                "residual" => $value['quantity'],
                "amount" => $value['amount'],
                "batch" => $batch,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];

        }
        StorageModel::query()->insert($result);
        return response()->json([
            "status" => true,
            "message" => "successfully"
        ]);
    }

    /**
     * @param int $categoryId
     * @param int $providerId
     * @return ProviderModel|null
     */
    public function getProviderByCategoryId(int $categoryId, int $providerId): ?ProviderModel
    {
        do {
            $category = $this->getCategory($categoryId);
            $categoryId = $category->category_id;
        } while ($category->category_id);
        return ProviderModel::query()
            ->where('id', '=', $providerId)
            ->where('category_id', '=', $category->id)
            ->first();
    }

    /**
     * @param int $categoryId
     * @return CategoryModel
     */
    public function getCategory(int $categoryId): CategoryModel
    {
        return CategoryModel::query()->where('id', '=', $categoryId)->first();
    }

    /**
     * @param int $id
     * @return ProductModel
     */
    public function getProduct(int $id): ProductModel
    {
        return ProductModel::query()->where('id', '=', $id)->first();
    }

    /**
     * @return int
     */
    public function getBatch(): int
    {
        $batch = StorageModel::query()
            ->orderByDesc('batch')
            ->first()?->batch;

        return ++$batch;
    }

    public function refund(Request $request):JsonResponse
    {
        foreach ($request->get('batches') as $item => $value) {
            $storage = StorageModel::query()
                ->where('batch', '=', $value['batch'])
                ->where('product_id', '=', $value['product_id'])
                ->first();
            if (!$storage) {
                return response()->json([
                    "status" => "false",
                    "message" => "The batches.$item.batch field is invalid",
                    "errors" => [
                        "batches.$item.batch" => [
                            "The batches.$item.batch field is invalid"
                        ]
                    ],
                    "code" => 422
                ], 422);
            }
            $residual = $storage->residual - $value['quantity'];

            if ($residual < 0) {
                return response()->json([
                    "status" => "false",
                    "message" => "The batches.$item.quantity field is invalid",
                    "errors" => [
                        "batches.$item.quantity" => [
                            "The batches.$item.quantity field is invalid"
                        ]
                    ],
                    "code" => 422
                ], 422);
            }
            StorageModel::query()
                ->where('batch', '=', $value['batch'])
                ->where('product_id', '=', $value['product_id'])
                ->update([
                    "residual" => $residual
                ]);

        }
        return response()->json([
            "status" => true,
            "message" => "successfully"
        ]);
    }
}
