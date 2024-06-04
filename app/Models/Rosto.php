<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rosto extends Model
{
    use HasFactory;

    protected $fillable = ['id_pessoa', 'url_rosto'];

    public function pessoas()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
