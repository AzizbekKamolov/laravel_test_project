<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyingProductsRequest;
use App\Http\Requests\MakeOrderRequest;
use App\Http\Requests\RefundRequest;
use App\Models\BatchModel;
use App\Models\ProductModel;
use App\Models\StorageModel;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $products = ProductModel::query()
            ->select([
                "products.id",
                "products.name",
                "s.sell_residual as quantity",
                "batch",
            ])
//            ->whereIn('id', function ($query){
//                $query->from('storages')
//                    ->select('product_id')
//                    ->where('sell_residual', '>', 0)
//                    ->groupBy('product_id');
//            })
            ->join('storages as s', 's.product_id', '=', 'products.id')
            ->where('s.sell_residual', '>', 0)
            ->join('batches as b', 'b.id', '=', 's.batch_id')
            ->orderBy('products.id')
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

    /**
     * @param int $batch
     * @return JsonResponse
     */
    public function calculateProfit(int $batch): JsonResponse
    {
        $batchModel = BatchModel::query()->where('batch', '=', $batch)->first();
        if (!$batchModel) {
            return $this->errorRes('Batch not found', 404);
        }
        $data = StorageModel::query()
            ->select([
                "storages.product_id",
                "p.name as product_name",
                DB::raw("storages.amount - o.amount as profit"),
                "storages.sell_residual as residual",
            ])
            ->join(DB::raw("(select batch_id, product_id, sum(amount) as amount from orders
                    where batch_id = {$batchModel->id} group by product_id) as o"), function ($query) {
                $query->on('o.batch_id', '=', 'storages.batch_id')->on('o.product_id', '=', 'storages.product_id');
            })
            ->join('products as p', 'p.id', '=', 'storages.product_id')
            ->get();
        return $this->successRes($data->toArray());
    }
}
