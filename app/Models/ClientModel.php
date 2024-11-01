<?php

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property string $name;
 * @property string $created_at;
 * @property string $updated_at;
 */
class ClientModel extends Model
{
    use EloquentFilterTrait;

    protected $table = 'clients';
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];
}
