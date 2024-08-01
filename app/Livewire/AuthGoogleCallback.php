<?php

namespace App\Livewire;

use Livewire\Component;
use Google\Client;
use Google\Service\Drive;

class AuthGoogleCallback extends Component {

  public $authUrl;

  public function mount()
  {
    $cliente = $this->getClienteGoogle();

    if (!request('code')) {
      $state = bin2hex(random_bytes(16));
      $cliente->setState($state);
      session(['state' => $state]);
      $authUrl = $cliente->createAuthUrl();
      return redirect($authUrl);
    } else {
      $cliente->fetchAccessTokenWithAuthCode(request('code'));
      session(['access_token' => $cliente->getAccessToken()]);
      return redirect()->route('home');  
    }
  }

  protected function getClienteGoogle()
  {
      $cliente = new Client();
      $cliente->setAuthConfig(storage_path('app\\client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
      $cliente->addScope(Drive::DRIVE);
      $cliente->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
      $guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
      $cliente->setHttpClient($guzzleClient);
      return $cliente;
  }

  public function render()
  {
    return view('livewire.auth-google-callback');
  }
}