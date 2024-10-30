<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyingProductsRequest;
use App\Http\Requests\MakeOrderRequest;
use App\Http\Requests\RefundRequest;
use App\Models\StorageModel;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function __construct(protected StorageService $service)
    {

    }

    /**
     * @param BuyingProductsRequest $request
     * @return JsonResponse
     */
    public function buyingProducts(BuyingProductsRequest $request): JsonResponse
    {
        return $this->service->buyingProducts($request);
    }

    /**
     * @param RefundRequest $request
     * @return JsonResponse
     */
    public function refund(RefundRequest $request): JsonResponse
    {
        return $this->service->refund($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getProducts(Request $request): JsonResponse
    {
        $products = StorageModel::query()
            ->select([
                'p.id',
                'p.name',
            ])
            ->leftJoin('products as p', 'p.id', '=', 'storages.product_id')
            ->groupBy('storages.product_id')
            ->paginate($request->get('per_page', 15));
        return $this->paginateRes($products);
    }

    /**
     * @param MakeOrderRequest $request
     * @return JsonResponse
     */
    public function makeOrder(MakeOrderRequest $request): JsonResponse
    {
        return $this->service->makeOrder($request);
    }

    public function calculateProfit(int $batchId)
    {

        dd($batchId);
    }
}
