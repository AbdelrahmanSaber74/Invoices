<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Product_name',
        'description',
        'section_id',
    ];


    
public function sections()
{
    return $this->belongsTo(section::class , 'section_id') ;
}


}
