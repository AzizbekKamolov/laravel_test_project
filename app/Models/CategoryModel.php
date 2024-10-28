<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id;
 * @property string $name;
 * @property int $category_id;
 * @property string $created_at;
 * @property string $updated_at;
 */
class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        "name",
        "category_id",
        "created_at",
        "updated_at",
    ];
}
