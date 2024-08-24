<?php

namespace App\Livewire;

use App\Services\GoogleService;
use Livewire\Component;

class PastaDownloadServidor extends Component 
{
    public $arquivos = [];
    public $idPasta = 'root';
    public $historicoPastas = [];
    public $caminhoPasta = '';
    public $redirectUrl = null;
    public $retonarRota;

    public function mount($retonarRota = 'home') 
    {
        $this->retonarRota = $retonarRota;
        $this->listarArquivos();
        $this->caminhoPasta = $this->obterCaminhoAtualPasta();
    }

    public function render() 
    {   
        return view('livewire.pasta-download-servidor', [
            'arquivos' => $this->arquivos,
        ]);
    }

    public function listarArquivos() 
    {
        $googleServico = new GoogleService();
        $cliente = $googleServico->getClient();
    
        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));
            $drive = $googleServico->getDriveService();
    
            $query = "'{$this->idPasta}' in parents and (mimeType contains 'application/vnd.google-apps.folder' or mimeType contains 'image/jpeg' or mimeType contains 'image/png' or mimeType contains 'image/gif')";
            $resultados = $drive->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name, mimeType, webViewLink)'
            ]);
    
            $this->arquivos = array_map(fn($arquivo) => [
                'id' => $arquivo->getId(),
                'nome' => $arquivo->getName(),
                'tipoMime' => $arquivo->getMimeType(),
                'linkVisualizacao' => $arquivo->getWebViewLink(),
            ], $resultados->getFiles());
        } else {
            return redirect($cliente->createAuthUrl());
        }
    }
    
    public function obterCaminhoAtualPasta() 
    {
        $googleServico = new GoogleService();
        $cliente = $googleServico->getClient();

        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));
            $drive = $googleServico->getDriveService();

            $caminhoPasta = '';
            $idPasta = $this->idPasta;
            while ($idPasta != 'root') {
                $pasta = $drive->files->get($idPasta, ['fields' => 'id, name, parents']);
                $caminhoPasta = $pasta->name . '/' . $caminhoPasta;
                $idPasta = $pasta->parents[0] ?? 'root';
            }
            return '/' . trim($caminhoPasta, '/');
        } else {
            return redirect($cliente->createAuthUrl());
        }
    }

    public function alterarPasta($idPasta) 
    {
        array_push($this->historicoPastas, $this->idPasta);
        $this->idPasta = $idPasta;
        $this->listarArquivos();
        $this->caminhoPasta = $this->obterCaminhoAtualPasta();
    }

    public function selecionar() 
    {
        session()->put('caminhoPastaGoogleDrive',  $this->idPasta);
        return redirect()->route($this->retonarRota); 
    }

    public function voltar() 
    {
        if (!empty($this->historicoPastas)) {
            $this->idPasta = array_pop($this->historicoPastas);
            $this->listarArquivos();
            $this->caminhoPasta = $this->obterCaminhoAtualPasta();
        }
    }       
}
