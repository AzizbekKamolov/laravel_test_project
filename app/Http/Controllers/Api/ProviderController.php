<?php

namespace App\Http\Controllers\Api;

use App\Filters\Provider\ProviderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProviderRequest;
use App\Models\ProviderModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters[] = ProviderFilter::getRequest($request);

        $providers = ProviderModel::applyEloquentFilters($filters)
//            ->with('categories')
            ->select('id', 'name', 'category_id')
            ->paginate($request->get('per_page', 10));
        return $this->paginateRes($providers);
    }

    /**
     * @param ProviderRequest $request
     * @return JsonResponse
     */
    public function store(ProviderRequest $request): JsonResponse
    {
        ProviderModel::query()->create($request->validated());
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $provider = ProviderModel::query()->find($id);
        if (!$provider) {
            return $this->errorRes('Provider not found', 404, 404);
        }
        return $this->successRes($provider->toArray());
    }

    /**
     * @param ProviderRequest $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(ProviderRequest $request, int $id): JsonResponse
    {
        $provider = ProviderModel::query()->find($id);
        if (!$provider) {
            return $this->errorRes('Provider not found', 404, 404);
        }
        $provider->fill($request->validated());
        $provider->save();
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */

    public function destroy(int $id): JsonResponse
    {
        $provider = ProviderModel::query()->where('id', '=', $id)->first();
        if (!$provider) {
            return $this->errorRes('Provider not found', 404, 404);
        }
        $provider->delete();
        return $this->successRes();
    }
}
