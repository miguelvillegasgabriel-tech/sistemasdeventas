<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;


class Producto extends Model
{
    use HasFactory;

    public function compras():BelongsToMany{

        return $this->belongsToMany(Compra::class)->withTimestamps()->withPivot('cantidad','precio_compra','precio_venta');

    }

    public function ventas():BelongsToMany{

        return $this->belongsToMany(Venta::class)->withTimestamps()->withPivot('cantidad','precio_venta','descuento');
        
    }

    public function categoria():BelongsToMany{

        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }

    public function marca():BelongsTo
    {

        return $this->belongsTo(Marca::class);
    }
    public function presentacione():BelongsTo{

        return $this->belongsTo(Presentacione::class);
    }

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'fecha_vencimiento','marca_id','presentacione_id', 'img_path'];

    public function handleUploadImage($image):string
    {

        
        $file = $image;
        // $name = time() . $file->getClientOriginalName();
        $name = time() . '_' . $file->getClientOriginalName(); // para evitar duplicados
        // $file->move(public_path() . '/img/productos/', $name);
        // Storage::putFileAs('/public/productos/',$file,$name,'public');
        Storage::disk('public')->putFileAs('productos', $image, $name);

      

       
        return $name; 


    }
}
