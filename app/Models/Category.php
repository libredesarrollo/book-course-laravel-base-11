<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'slug'];

    // protected function title(): Attribute {
    //     return Attribute::make(
    //         get: fn (string $value) => ucfirst($value),
    //         set: fn (string $value) => strtolower('Fijo')
    //     );
    // }

    // function getTitleAttribute(?string $value): ?string
    // {
    //     if ($value)
    //         return ucfirst($value);
    //     return '-';
    // }
    // function setTitleAttribute(string $value): string
    // {
    //     return $this->attributes['title'] = 'fijo4';
    // }

    function posts()
    {
        return $this->hasMany(Post::class);
    }
}
