<?php

namespace App\Http\Middleware;

use Closure;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Auth;

class GoogleAuthenticate
{
    public function handle($request, Closure $next)
    {
        $user_id = Auth::id();

        if (!$user_id) {
            return $next($request);
        }

        $client = $this->createGoogleClient();

        if (session()->has('access_token')) {
            $client->setAccessToken(session('access_token'));

            if ($client->isAccessTokenExpired()) {
                return $this->handleExpiredToken($client, $request);
            }

            return $next($request);
        }

        if ($request->has('code')) {
            return $this->handleAuthCode($client, $request);
        }

        return $this->redirectToGoogle($client);
    }

    private function createGoogleClient()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
        $client->setRedirectUri(route('home')); 
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $client->setHttpClient($guzzleClient);
        $client->addScope(Drive::DRIVE);

        return $client;
    }

    private function handleExpiredToken($client, $request)
    {
        $refreshToken = session('refresh_token');
        if ($refreshToken) {
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            session(['access_token' => $client->getAccessToken()]);
            return $request->next();
        }

        return $this->redirectToGoogle($client);
    }

    private function handleAuthCode($client, $request)
    {
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));
        session()->put('access_token', $client->getAccessToken());

        if (isset($token['refresh_token'])) {
            session()->put('refresh_token', $token['refresh_token']);
        }

        return redirect()->to($request->url());
    }

    protected function redirectToGoogle($client)
    {
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }
}
