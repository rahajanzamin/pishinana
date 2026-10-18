<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'description', 'sort'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
