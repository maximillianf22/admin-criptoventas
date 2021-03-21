<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    public function getCategory()
    {
        return $this->belongsTo(IngredientCategory::class, 'ingredient_category_id');
    }
}
