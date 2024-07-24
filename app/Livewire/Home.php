<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }

/*    public function mount()
    {
        $clientId = getenv('GOOGLE_CLIENT_ID');
        $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
        $scope = getenv('GOOGLE_DRIVE_SCOPE');

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
        $client->setScopes($scope);

        $authUrl = $client->createAuthUrl();
        if (session('access_token')) {
        $client->setAccessToken(session('access_token'));
        $drive = new Drive($client);
        $files = $drive->files->listFiles(array())->getFiles();
            dd($files);
        } else {
            return redirect($authUrl);
        }
    } */
}
