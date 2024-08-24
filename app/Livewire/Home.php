<?php

namespace App\Livewire;

use Google\Client;
use Google\Service\Drive;
use Livewire\Component;

class Home extends Component {
    public function mount() 
    {
        session()->put('caminhoPastaGoogleDrive',  '');

        $cliente = $this->getGoogleClient();
    
        // Verifica se existe um token de acesso armazenado na sessão
        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));

            // Verifica se o token de acesso expirou
            if ($cliente->isAccessTokenExpired()) {
                // Tenta renovar o token usando o refresh token
                if ($cliente->getRefreshToken()) {
                    $cliente->fetchAccessTokenWithRefreshToken($cliente->getRefreshToken());
                    session(['access_token' => $cliente->getAccessToken()]);
                } else {
                    // Se não houver refresh token, redireciona o usuário para a autorização
                    $authUrl = $cliente->createAuthUrl();
                    return redirect($authUrl);
                }
            }

            $drive = new Drive($cliente);
            $files = $drive->files->listFiles(array())->getFiles();

        } else {
            $authUrl = $cliente->createAuthUrl();
            return redirect($authUrl);
        }
    }

    public function render() 
    {
        return view('livewire.home');
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
}
