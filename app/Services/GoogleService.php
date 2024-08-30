<?php

namespace App\Services;

use DateTime;
use Exception;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use GuzzleHttp\Promise\Utils;
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

        $this->guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $this->client->setHttpClient($this->guzzleClient);

        $this->client->setAccessToken(session('access_token'));
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getDriveService()
    {
        return new Drive($this->client);
    }

    public function baixarPasta($filtroDataInicio, $filtroDataFim)
    {
        $drive = $this->getDriveService();
         
        // Limpeza do diretório temporário
        $tempDir = 'public\\' .$this->login_id_usuario .'\\temp';
        if (Storage::exists($tempDir)) {      
            Storage::deleteDirectory($tempDir);
        }

        if (!Storage::exists($tempDir)) {        
            Storage::makeDirectory($tempDir);
        }
        
        $tempDir = storage_path('app\\public\\' .$this->login_id_usuario .'\\temp\\');
        
        $this->baixarArquivosEmLote($drive, session('caminhoPastaGoogleDrive'), $tempDir, $filtroDataInicio, $filtroDataFim);
    }

    public function baixarArquivosEmLote($drive, $pastaId, $caminho, $filtroDataInicio, $filtroDataFim)
    {
        try {

            $resultados = $drive->files->listFiles([
                'q' => "'{$pastaId}' in parents",
                'fields' => 'files(id, name, mimeType, modifiedTime)'
            ]);
            
            $arquivos = $resultados->getFiles();

            $promises = [];
            
            foreach ($arquivos as $arquivo) {
                if ($arquivo->mimeType == 'application/vnd.google-apps.folder') {
                    $novoCaminho = $caminho .$arquivo->name .'/';
                    if (!Storage::exists($novoCaminho)) {
                        Storage::makeDirectory($novoCaminho);
                    }

                    static $pastasProcessadas = [];
                    if (isset($pastasProcessadas[$arquivo->id])) {
                        continue;
                    }

                    $pastasProcessadas[$arquivo->id] = true;
                    $this->baixarArquivosEmLote($drive, $arquivo->id, $novoCaminho, $filtroDataInicio, $filtroDataFim);
                } else {      
                    // Convertendo createdTime para um objeto DateTime.
                    $dataModificacao = $arquivo->modifiedTime;  

                    // Obtém a data de modificação e formata
                    $dataModificacaoFormatada = new DateTime($arquivo->modifiedTime);
                    $dataModificacaoFormatada = $dataModificacaoFormatada->format('Y-m-d');
                    
                    // Verifica se possui filtro referente a data de modifição das fotos da pasta selecionada.
                    if ($filtroDataInicio == 'None' || ($filtroDataFim == 'None')) {
                        $promises[$dataModificacaoFormatada .'_' .$arquivo->name] = $this->guzzleClient->requestAsync('GET', 'https://www.googleapis.com/drive/v3/files/' . $arquivo->id . '?alt=media', [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $drive->getClient()->getAccessToken()['access_token']
                            ]
                        ]);
                    } else {
                        // Convertendo as datas de início e fim para o formato Y-m-d, se necessário.
                        $dataInicioFormatada = DateTime::createFromFormat('d/m/Y', $filtroDataInicio);
                        $dataFimFormatada = DateTime::createFromFormat('d/m/Y', $filtroDataFim);

                        //dd(
                        //    $arquivo->name .' ' 
                        //    .Carbon::parse($dataModificacao)->startOfDay() .' : ' 
                        //    .Carbon::parse($dataInicioFormatada)->startOfDay() .' - ' 
                        //    .Carbon::parse($dataFimFormatada)->startOfDay()
                        //);

                        // Verificando se a data de criação está dentro do intervalo.
                        if ((Carbon::parse($dataInicioFormatada)->startOfDay() <= Carbon::parse($dataModificacao)->startOfDay()) && 
                            (Carbon::parse($dataModificacao)->startOfDay() <= Carbon::parse($dataFimFormatada)->startOfDay())) {                                    

                            $promises[$dataModificacaoFormatada .'_' .$arquivo->name] = $this->guzzleClient->requestAsync('GET', 'https://www.googleapis.com/drive/v3/files/' . $arquivo->id . '?alt=media', [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $drive->getClient()->getAccessToken()['access_token']
                                ]
                            ]);
                        } 
                    }               
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
