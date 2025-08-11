<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    use HasFactory;

     // Permitir asignación masiva
    protected $fillable = ['tipo_documento'];

    public function persona():HasMany
    {
        
        return $this->hasMany(Persona::class);
    }
}
