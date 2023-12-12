<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municities';

    public function address(){
        return $this->hasMany(Address::class, 'municipality', 'adm3_pcode');
    }
}
