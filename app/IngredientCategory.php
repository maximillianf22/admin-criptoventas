<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientCategory extends Model
{
    protected $table = 'ingredient_categories';

    public function getIngredients()
    {
        return $this->hasMany(Ingredient::class, 'ingredient_category_id', 'id');
    }
}
