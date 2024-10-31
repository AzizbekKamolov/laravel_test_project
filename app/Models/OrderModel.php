<?php

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property int $client_id;
 * @property int $product_id;
 * @property int $quantity;
 * @property int $batch_id;
 * @property int $amount;
 * @property string $created_at;
 * @property string $updated_at;
 */
class OrderModel extends Model
{
    use EloquentFilterTrait;

    protected $table = 'orders';
    protected $fillable = [
        "client_id",
        "product_id",
        "quantity",
        "batch_id",
        "amount",
        "created_at",
        "updated_at",
    ];
}
