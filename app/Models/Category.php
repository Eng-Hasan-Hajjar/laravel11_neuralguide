<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon', 'color'];

    public function architectures()
    {
        return $this->belongsToMany(Architecture::class, 'architecture_category');
    }
}
