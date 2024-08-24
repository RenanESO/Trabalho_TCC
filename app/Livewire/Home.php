<?php

namespace App\Livewire;

use App\Services\GoogleService;
use Livewire\Component;

class Home extends Component {

    protected $googleDriveClient;

    public function mount(GoogleService $googleDriveClient)
    {
        session()->put('caminhoPastaGoogleDrive',  '');

        // Realiza a autenticação da API do Google Drive.
        $this->googleDriveClient = $googleDriveClient;
        $this->googleDriveClient->authenticate();
    }

    public function render() 
    {
        return view('livewire.home');
    }
}
