<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klienti extends Model
{
    protected $table = 'klientis';
    use HasFactory;
     protected $fillable = [
        'emri',
        'mbiemri',
        'telefoni',
        'produkti',
        'sasia',
        'qmimi',
         'user_id'

    ];
}

