<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Filters\Category\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\CategoryModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters[] = CategoryFilter::getRequest($request);

        $categories = CategoryModel::applyEloquentFilters($filters)
//            ->with('categories')
            ->select('id', 'name', 'category_id')
            ->paginate($request->get('per_page', 10));
        return $this->paginateRes($categories);
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        CategoryModel::query()->create($request->validated());
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $category = CategoryModel::query()->find($id);
        if (!$category) {
            return $this->errorRes('Category not found', 404, 404);
        }
        return $this->successRes($category->toArray());
    }

    /**
     * @param CategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = CategoryModel::query()->find($id);
        if (!$category) {
            return $this->errorRes('Category not found', 404, 404);
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
        $category = CategoryModel::query()->where('id', '=', $id)->first();
        if (!$category) {
            return $this->errorRes('Category not found', 404, 404);
        }
        $category->delete();
        return $this->successRes();
    }
}
