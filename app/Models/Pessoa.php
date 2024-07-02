<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'user_id'];

    public function rostos()
    {
        return $this->hasMany(Rosto::class, 'id_pessoa', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
