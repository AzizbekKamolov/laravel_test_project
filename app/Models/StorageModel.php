<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property int $provider_id;
 * @property int $product_id;
 * @property int $quantity;
 * @property int $refund_residual;
 * @property int $sell_residual;
 * @property int $amount;
 * @property int $refunded_amount;
 * @property int $batch_id;
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
        "refund_residual",
        "sell_residual",
        "amount",
        "refunded_amount",
        "batch_id",
        "created_at",
        "updated_at",
    ];
}
