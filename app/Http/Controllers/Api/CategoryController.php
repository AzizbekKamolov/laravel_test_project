<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

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
        $categories = CategoryModel::query()->paginate($request->get('per_page', 10));
        return $this->paginateRes($categories);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        CategoryModel::query()->create($request->validated());
        return $this->successRes();
    }

    public function getOne(int $id): JsonResponse
    {
        return $this->successRes(CategoryModel::query()->findOrFail($id)->toArray());
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = CategoryModel::query()->findOrFail($id);
        $category->fill($request->validated());
        $category->save();
        return $this->successRes();
    }

    public function destroy(int $id): JsonResponse
    {
        $category = CategoryModel::query()->findOrFail($id);
        $category->delete();
        return $this->successRes();
    }
}
