<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\BatchModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\StorageModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageService
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function buyingProducts(Request $request): JsonResponse
    {
        $batch = $this->getLastBatch();
        $result = [];
        foreach ($request->get('products') as $value) {
            $product = $this->getProduct($value['product_id']);
            $provider = $this->checkProviderByCategoryId($product->category_id, $request->get('provider_id'));

            if (!$provider) {
                return response()->json([
                    "status" => false,
                    "message" => "The provider_id field is invalid",
                    "errors" => [
                        "provider_id" => [
                            "The provider_id field is invalid"
                        ]
                    ],
                ], 422);
            }
            $result[] = [
                "product_id" => $value['product_id'],
                "quantity" => $value['quantity'],
                "refund_residual" => $value['quantity'],
                "sell_residual" => $value['quantity'],
                "amount" => $value['amount'],
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];

        }
        $batchModel = BatchModel::query()->create([
            "provider_id" => $request->get('provider_id'),
            "batch" => $batch
        ]);

        array_walk($result, fn(&$arr) => $arr["batch_id"] = $batchModel->id);

        StorageModel::query()->insert($result);

        return response()->json([
            "status" => true,
            "message" => "successfully"
        ]);
    }

    /**
     * @param int $categoryId
     * @param int $providerId
     * @return bool
     */
    public function checkProviderByCategoryId(int $categoryId, int $providerId): bool
    {
        do {
            $category = $this->getCategory($categoryId);
            $categoryId = $category->category_id;
        } while ($category->category_id);

        return $category->provider_id === $providerId;
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
    public function getLastBatch(): int
    {
        $batch = BatchModel::query()
            ->orderByDesc('batch')
            ->first()?->batch;

        return ++$batch;
    }

    public function refund(Request $request): JsonResponse
    {
        $batch = BatchModel::query()->where('batch', '=', $request->get('batch'))->first();
        $storage = StorageModel::query()
            ->where('batch_id', '=', $batch->id)
            ->where('product_id', '=', $request->get('product_id'))
            ->first();
        if (!$storage) {
            return response()->json([
                "status" => false,
                "message" => "The batch field is invalid",
                "errors" => [
                    "batch" => [
                        "The batch field is invalid"
                    ]
                ],
            ], 422);
        }
        $residual = $storage->refund_residual - $request->get('quantity');

        if ($residual < 0) {
            return response()->json([
                "status" => false,
                "message" => "The quantity field is invalid",
                "errors" => [
                    "quantity" => [
                        "The quantity field is invalid"
                    ]
                ],
            ], 422);
        }
        $storage->update([
            "refund_residual" => $residual,
            "refunded_amount" => DB::raw("refunded_amount + " . $request->get('amount'))
        ]);

        return response()->json([
            "status" => true,
            "message" => "successfully"
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function makeOrder(Request $request): JsonResponse
    {

        $result = [];
        $batch = BatchModel::query()->where('batch', '=', $request->get('batch'))->first();
        foreach ($request->get('products') as $item => $value) {
            $storage = StorageModel::query()
                ->where('product_id', '=', $value['product_id'])
                ->where('batch_id', '=', $batch->id)
                ->where('sell_residual', '>=', $value['quantity'])
                ->first();

            if (!$storage) {
                return response()->json([
                    "status" => false,
                    "message" => "There is not enough product in storage",
                    "errors" => [
                        "products.$item.quantity" => [
                            "The products.$item.quantity field is invalid"
                        ]
                    ],
                ], 422);
            }
            $result[] = [
                "client_id" => $request->get('client_id'),
                "product_id" => $value['product_id'],
                "quantity" => $value['quantity'],
                "batch" => $batch->batch,
                "amount" => $value['amount'],
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];
        }
        foreach ($result as $order) {
            StorageModel::query()
                ->where('product_id', '=', $order['product_id'])
                ->where('batch_id', '=', $batch->id)
                ->update([
                    "sell_residual" => DB::raw("sell_residual - {$order['quantity']}"),
                ]);
        }
        OrderModel::query()->insert($result);
        return response()->json([
            "status" => true,
            "message" => "Successfully",
        ]);
    }
}
