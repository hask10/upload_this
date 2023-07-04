<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    # guarded
    protected $guarded = [
        ''
    ];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function templateUsage()
    {
        return $this->hasMany(TemplateUsage::class);
    }
}
