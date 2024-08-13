<?php

namespace App\Livewire;

use Google\Client;
use Google\Service\Drive;
use Livewire\Component;

class Home extends Component 
{
    public function mount()
    {
        session()->put('caminhoPastaGoogleDrive',  '');

        $cliente = $this->getGoogleClient();
    
        $authUrl = $cliente->createAuthUrl();
        if (session('access_token')) {
            $cliente->setAccessToken(session('access_token'));
            $drive = new Drive($cliente);
            $files = $drive->files->listFiles(array())->getFiles();
            //dd($files);
        } else {
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
