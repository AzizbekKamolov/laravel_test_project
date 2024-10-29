<?php
declare(strict_types=1);

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property string $name;
 * @property int $category_id;
 * @property string $created_at;
 * @property string $updated_at;
 */
class ProviderModel extends Model
{
    use EloquentFilterTrait;

    protected $table = 'providers';
    protected $fillable = [
        "name",
        "category_id",
        "created_at",
        "updated_at",
    ];
}
