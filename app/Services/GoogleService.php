<?php

namespace App\Services;

use Exception;
use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class GoogleService
{
    protected $client;
    protected $login_id_usuario;
    protected $guzzleClient;

    public function __construct()
    {
        $this->login_id_usuario = Auth::id();
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
        $this->client->addScope(Drive::DRIVE);

        if (App::environment('local')) {
            $this->client->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
        } elseif (App::environment('production')) {
            $this->client->setRedirectUri('https://projetosdevrenan.online/public/oauth-google-callback');
        }

        $this->guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $this->client->setHttpClient($this->guzzleClient);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getDriveService()
    {
        return new Drive($this->client);
    }

    public function authenticate()
    {
        if (session('access_token')) {
            $this->client->setAccessToken(session('access_token'));

            // Verifica se o token de acesso expirou
            if ($this->client->isAccessTokenExpired()) {
                // Tenta renovar o token usando o refresh token
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    session(['access_token' => $this->client->getAccessToken()]);
                } else {
                    // Se não houver refresh token, redireciona o usuário para a autorização
                    $authUrl = $this->client->createAuthUrl();
                    return redirect($authUrl);
                }
            }
        } else {
            $authUrl = $this->client->createAuthUrl();
            return redirect($authUrl);
        }
    }

    public function baixarPasta()
    {

        if (!session('access_token')) {
            return redirect($this->client->createAuthUrl());
        }

        $this->client->setAccessToken(session('access_token'));
        $drive = new Drive($this->client);
        // $drive->files->get();
        $tempDir = storage_path('app/public/' . $this->login_id_usuario .'/temp/');
        
        // Limpeza do diretório temporário
        if (Storage::exists($tempDir)) {
            Storage::deleteDirectory($tempDir);
        }

        if (!Storage::exists($tempDir)) {
            Storage::makeDirectory($tempDir);
        }
        
        $this->baixarArquivosEmLote($drive, session('caminhoPastaGoogleDrive'), $tempDir);
    }

    public function baixarArquivosEmLote($drive, $pastaId, $caminho)
    {
        try {

            $resultados = $drive->files->listFiles([
                'q' => "'{$pastaId}' in parents",
                'fields' => 'files(id, name, mimeType)'
            ]);
            
            $arquivos = $resultados->getFiles();

            $promises = [];
            
            foreach ($arquivos as $arquivo) {
                if ($arquivo->mimeType == 'application/vnd.google-apps.folder') {
                    $novoCaminho = $caminho . $arquivo->name . '/';
                    if (!Storage::exists($novoCaminho)) {
                        Storage::makeDirectory($novoCaminho);
                    }

                    static $pastasProcessadas = [];
                    if (isset($pastasProcessadas[$arquivo->id])) {
                        continue;
                    }

                    $pastasProcessadas[$arquivo->id] = true;
                    $this->baixarArquivosEmLote($drive, $arquivo->id, $novoCaminho);
                } else {      
                    $promises[$arquivo->name] = $this->guzzleClient->requestAsync('GET', 'https://www.googleapis.com/drive/v3/files/' . $arquivo->id . '?alt=media', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $drive->getClient()->getAccessToken()['access_token']
                        ]
                    ]);
                    
                }
            }

            $responses = Utils::all($promises)->wait();


            foreach ($responses as $fileId => $response) {
                if ($response instanceof \Google_Service_Exception) {
                    // Trata exceções específicascontinue;
                }

                $conteudo = $response->getBody()->getContents();
                Storage::put('\\public\\' . $this->login_id_usuario . '\\temp\\' . $fileId, $conteudo);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Erro ao baixar arquivos da pasta: ' . $pastaId . ' .Erro: ' . $e->getMessage());
        }
    }
}
