<?php

namespace App\Models;

use App\Filters\Trait\EloquentFilterTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id;
 * @property string $name;
 * @property int $category_id;
 * @property string $created_at;
 * @property string $updated_at;
 */
class CategoryModel extends Model
{
    use EloquentFilterTrait;
    protected $table = 'categories';
    protected $fillable = [
        "name",
        "category_id",
        "created_at",
        "updated_at",
    ];

    /**
     * @return HasMany
     */
    public function categories():HasMany
    {
        return $this->hasMany(self::class, 'category_id', 'id');
    }
}
