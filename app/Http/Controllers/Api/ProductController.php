<?php

namespace App\Http\Controllers\Api;

use App\Filters\Product\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\ProductModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters[] = ProductFilter::getRequest($request);

        $products = ProductModel::applyEloquentFilters($filters)
            ->select('id', 'name', 'category_id')
            ->paginate($request->get('per_page', 10));
        return $this->paginateRes($products);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        ProductModel::query()->create($request->validated());
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $category = ProductModel::query()->find($id);
        if (!$category) {
            return $this->errorRes('Product not found', 404, 404);
        }
        return $this->successRes($category->toArray());
    }

    /**
     * @param ProductRequest $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        $category = ProductModel::query()->find($id);
        if (!$category) {
            return $this->errorRes('Product not found', 404, 404);
        }
        $category->fill($request->validated());
        $category->save();
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */

    public function destroy(int $id): JsonResponse
    {
        $category = ProductModel::query()->where('id', '=', $id)->first();
        if (!$category) {
            return $this->errorRes('Product not found', 404, 404);
        }
        $category->delete();
        return $this->successRes();
    }
}
