<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyingProductsRequest;
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

    public function buyingProducts(BuyingProductsRequest $request): JsonResponse
    {
        return $this->service->buyingProducts($request);
    }

    public function refund(RefundRequest $request): JsonResponse
    {
        return $this->service->refund($request);
    }

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

    public function makeOrder()
    {

    }
}
