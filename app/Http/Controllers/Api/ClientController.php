<?php

namespace App\Http\Controllers\Api;

use App\Filters\Client\ClientFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\ClientModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters[] = ClientFilter::getRequest($request);

        $clients = ClientModel::applyEloquentFilters($filters)
            ->select('id', 'name')
            ->paginate($request->get('per_page', 10));
        return $this->paginateRes($clients);
    }

    /**
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        ClientModel::query()->create($request->validated());
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse
    {
        $client = ClientModel::query()->find($id);
        if (!$client) {
            return $this->errorRes('Client not found', 404, 404);
        }
        return $this->successRes($client->toArray());
    }

    /**
     * @param ClientRequest $request
     * @param int $id
     * @return JsonResponse
     */

    public function update(ClientRequest $request, int $id): JsonResponse
    {
        $client = ClientModel::query()->find($id);
        if (!$client) {
            return $this->errorRes('Client not found', 404, 404);
        }
        $client->fill($request->validated());
        $client->save();
        return $this->successRes();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */

    public function destroy(int $id): JsonResponse
    {
        $client = ClientModel::query()->where('id', '=', $id)->first();
        if (!$client) {
            return $this->errorRes('Client not found', 404, 404);
        }
        $client->delete();
        return $this->successRes();
    }
}
