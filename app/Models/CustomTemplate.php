<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{
    use HasFactory;


    public function templateUsage()
    {
        return $this->hasMany(TemplateUsage::class, 'custom_template_id', 'id');
    }
}
