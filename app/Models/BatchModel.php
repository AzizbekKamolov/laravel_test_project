<?php

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property int $batch;
 * @property int $provider_id;
 * @property string $created_at;
 * @property string $updated_at;
 */
class BatchModel extends Model
{
    use EloquentFilterTrait;
    protected $table = 'batches';
    protected $fillable = [
      "batch",
      "provider_id",
      "created_at",
      "updated_at",
    ];
}
