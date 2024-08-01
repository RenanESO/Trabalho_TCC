<?php

namespace App\Livewire;

use Livewire\Component;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PastaDownloadServidor extends Component
{
    public $arquivos = [];
    public $idPasta = 'root';
    public $historicoPastas = [];
    public $caminhoPasta = '';
    public $redirectUrl = null;

    public function mount()
    {
        $this->listarArquivos();
        $this->caminhoPasta = $this->obterCaminhoAtualPasta();
    }

    public function render()
    {   
        return view('livewire.pasta-download-servidor', [
            'arquivos' => $this->arquivos,
        ]);
    }

    protected function getGoogleClient()
    {
        $cliente = new Client();
        $cliente->setAuthConfig(storage_path('app/client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
        $cliente->addScope(Drive::DRIVE);
        $cliente->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $cliente->setHttpClient($guzzleClient);
        return $cliente;
    }

    public function listarArquivos()
    {
        $cliente = $this->getGoogleClient();
    
        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));
            $drive = new Drive($cliente);
    
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
        $cliente = $this->getGoogleClient();

        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));
            $drive = new Drive($cliente);

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

    public function voltar()
    {
        if (!empty($this->historicoPastas)) {
            $this->idPasta = array_pop($this->historicoPastas);
            $this->listarArquivos();
            $this->caminhoPasta = $this->obterCaminhoAtualPasta();
        }
    }

    public function baixarPasta()
    {
        $client = $this->getGoogleClient();

        if (session('access_token')) {
            $client->setAccessToken(session('access_token'));
            $drive = new Drive($client);

            $tempDir = storage_path('app/temp/');
            Storage::makeDirectory($tempDir);

            $this->baixarArquivosRecursivamente($drive, $this->idPasta, $tempDir);

            $zip = new ZipArchive;
            $nomeArquivoZip = storage_path('app/public/') . $this->caminhoPasta . '.zip';

            if ($zip->open($nomeArquivoZip, ZipArchive::CREATE) === TRUE) {
                $this->adicionarArquivosAoZip($zip, $tempDir);
                $zip->close();
            }

            Storage::deleteDirectory($tempDir);

            return response()->download($nomeArquivoZip)->deleteFileAfterSend(true);
        } else {
            return redirect($client->createAuthUrl());
        }
    }

    public function baixarArquivosRecursivamente($drive, $pastaId, $caminho)
    {
        $resultados = $drive->files->listFiles([
            'q' => "'{$pastaId}' in parents",
            'fields' => 'files(id, name, mimeType)'
        ]);

        foreach ($resultados->getFiles() as $arquivo) {
            if ($arquivo->mimeType == 'application/vnd.google-apps.folder') {
                Storage::makeDirectory($caminho . $arquivo->name);
                $this->baixarArquivosRecursivamente($drive, $arquivo->id, $caminho . $arquivo->name . '/');
            } else {
                $conteudo = $drive->files->get($arquivo->id, ['alt' => 'media']);
                Storage::put($caminho . $arquivo->name, $conteudo->getBody()->getContents());
            }
        }
    }

    public function adicionarArquivosAoZip($zip, $caminho, $caminhoRelativo = '')
    {
        $arquivos = scandir($caminho);

        foreach ($arquivos as $arquivo) {
            if ($arquivo != '.' && $arquivo != '..') {
                if (is_dir($caminho . '/' . $arquivo)) {
                    $this->adicionarArquivosAoZip($zip, $caminho . '/' . $arquivo, $caminhoRelativo . $arquivo . '/');
                } else {
                    $zip->addFile($caminho . '/' . $arquivo, $caminhoRelativo . $arquivo);
                }
            }
        }
    }
        
}
