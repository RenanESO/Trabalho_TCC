<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'user_id'];

    public function buscaPessoasPorNome($query_filtro_pessoa)
    {
        $user = auth()->id(); 
        $listaPessoas = Pessoa::selectRaw('pessoas.id, pessoas.nome, pessoas.user_id, MIN(rostos.url_rosto) as url_rosto')
            ->leftJoin('rostos', 'rostos.id_pessoa', '=', 'pessoas.id')
            ->where('pessoas.user_id', $user)
            ->where('pessoas.nome', 'like', '%' . $query_filtro_pessoa . '%')
            ->groupBy('pessoas.id', 'pessoas.nome', 'pessoas.user_id')
            ->orderBy('pessoas.nome')
            ->paginate(10);
            return  $listaPessoas;
    }

    public function rostos()
    {
        return $this->hasMany(Rosto::class, 'id_pessoa', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
