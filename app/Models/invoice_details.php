<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_details extends Model
{
    use HasFactory;
    protected $guarded= [] ;

    public function sections()
    {
        return $this->belongsTo(section::class , 'section_id') ;
    }
    
}
