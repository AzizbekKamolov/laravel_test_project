<?php

namespace App\Filters\Product;

use App\Filters\EloquentFilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter implements EloquentFilterContract
{
    public function __construct(
        protected Request $request
    )
    {
    }

    public function applyEloquent(Builder $model): Builder
    {
        if ($this->request->filled('name')) {
            $model->where('name', 'like', '%' . $this->request->get('name') . '%');
        }
        if ($this->request->filled('category_id')) {
            $model->where('category_id', '=', $this->request->get('category_id'));
        }
        return $model;
    }

    public static function getRequest(Request $request): static
    {
        return new static($request);
    }
}
