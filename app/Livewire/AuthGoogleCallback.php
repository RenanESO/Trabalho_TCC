<?php

namespace App\Livewire;

use Livewire\Component;
use Google\Client;
use Google\Auth\Credentials;
use Google\Service\Drive;

class AuthGoogleCallback extends Component {

  public $authUrl;

  public function mount() {
    $clientId = getenv('GOOGLE_CLIENT_ID');
    $clientSecret = getenv('GOOGLE_CLIENT_SECRET');
    $scope = getenv('GOOGLE_DRIVE_SCOPE');

    $client = new Client();
    $client->setClientId($clientId);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
    $client->setScopes($scope);

    if (!request('code')) {
      $state = bin2hex(random_bytes(16));

      $client->setState($state);
      session(['state' => $state]);
      $authUrl = $client->createAuthUrl();
      return redirect($authUrl);
    } else {
      $client->fetchAccessTokenWithAuthCode(request('code'));
      session(['access_token' => $client->getAccessToken()]);

      $redirect_uri = 'http://127.0.0.1:8000/home';
      return redirect($redirect_uri);
    }
  }

  public function render()
  {
    //return view('livewire.auth-google-callback');
  }
}