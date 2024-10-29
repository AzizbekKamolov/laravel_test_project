<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property int $provider_id;
 * @property int $product_id;
 * @property int $quantity;
 * @property int $residual;
 * @property int $amount;
 * @property int $refunded_amount;
 * @property int $batch;
 * @property string $created_at;
 * @property int $updated_at;
 *
 */
class StorageModel extends Model
{
    protected $table = 'storages';
    protected $fillable = [
        "provider_id",
        "product_id",
        "quantity",
        "residual",
        "amount",
        "refunded_amount",
        "batch",
        "created_at",
        "updated_at",
    ];
}
