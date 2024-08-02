<?php

namespace App\Livewire;

use Livewire\Component;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
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
        try {
            $client = $this->getGoogleClient();

            if (!session('access_token')) {
                dd('Entrou 3');
                return redirect($client->createAuthUrl());
            }
    
            $client->setAccessToken(session('access_token'));
            $drive = new Drive($client);
    
            $tempDir = storage_path('app/temp/');
            if (!Storage::exists($tempDir)) {
                Storage::makeDirectory($tempDir);
            }
             
            $this->baixarArquivosRecursivamente($drive, $this->idPasta, $tempDir);

        

            $zip = new ZipArchive;
            $nomeArquivoZip = storage_path('app/public/') . $this->caminhoPasta . '.zip';
    
            if ($zip->open($nomeArquivoZip, ZipArchive::CREATE) === TRUE) {
                $this->adicionarArquivosAoZip($zip, $tempDir);
                $zip->close();
            } else {
                throw new \Exception('Não foi possível criar o arquivo zip.');
            }
    
            Storage::deleteDirectory($tempDir);
    
            return response()->download($nomeArquivoZip)->deleteFileAfterSend(true);
    
        } catch (\Exception $e) {
            // Log do erro
            //Log::error('Erro ao baixar pasta: ' . $e->getMessage());
            session()->flash('error', 'Ocorreu um erro interno, rotina "baixarPasta". Erro: ' .$e->getMessage());
    
            // Limpeza do diretório temporário em caso de erro
            if (Storage::exists($tempDir)) {
                Storage::deleteDirectory($tempDir);
            }
    
            // Redirecionar com mensagem de erro
            return redirect()->back()->withErrors(['error' => 'Ocorreu um erro ao baixar a pasta. Por favor, tente novamente.']);
        }
    }
    

    public function baixarArquivosRecursivamente($drive, $pastaId, $caminho)
    {
        Log::info("Baixando arquivos da pasta: {$pastaId} para o caminho: {$caminho}");
    
        try {
            $resultados = $drive->files->listFiles([
                'q' => "'{$pastaId}' in parents",
                'fields' => 'files(id, name, mimeType)'
            ]);
  
            foreach ($resultados->getFiles() as $arquivo) {
                

                Log::info("Processando arquivo/pasta: {$arquivo->name} ({$arquivo->id})");
    
                if ($arquivo->mimeType == 'application/vnd.google-apps.folder') {
                    $novoCaminho = $caminho . $arquivo->name . '/';
                    
                    if (!Storage::exists($novoCaminho)) {
                        Storage::makeDirectory($novoCaminho);
                    }
    
                    // Evitar loops infinitos verificando se a pasta já foi processada
                    static $pastasProcessadas = [];
                    if (isset($pastasProcessadas[$arquivo->id])) {
                        Log::warning("Loop detectado! Pasta {$arquivo->name} ({$arquivo->id}) já foi processada.");
                        continue;
                    }
    
                    $pastasProcessadas[$arquivo->id] = true;
                    $this->baixarArquivosRecursivamente($drive, $arquivo->id, $novoCaminho);
                } else {
                    $conteudo = $drive->files->get($arquivo->id, ['alt' => 'media']);
                    Storage::put($caminho . $arquivo->name, $conteudo->getBody()->getContents());
                    Log::info("Arquivo baixado: {$arquivo->name}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro ao baixar arquivos da pasta: {$pastaId}. Erro: " . $e->getMessage());
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
