<?php

namespace App\Livewire;

use App\Services\GoogleService;
use Livewire\Component;

class Home extends Component {

    protected $googleDriveClient;

    public function mount()
    {
        // Reseta a session referente ao caminho da pasta selecionada no Google Drive para vazio.
        session()->put('caminhoPastaGoogleDrive',  '');
    }

    public function render() 
    {
        return view('livewire.home');
    }
}
