<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Drive;

class VerificaDuplicidadeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $login_id_usuario;
    public $caminho_compilador_python;
    public $caminho_deteccao_python;
    public $caminho_arquivo_log;
    public $caminho_arquivo_pickle;
    public $caminho_arquivo_npy;
    public $filtro_caminho_origem;
    public $filtro_caminho_destino;
    public $filtro_data_inicial;
    public $filtro_data_final;
    public $filtro_copiar_recortar;
    public $habilitar_data; 
    public $habilitar_copiar_recortar;
    public $nome_botao_log; 

    /**
     * Create a new job instance.
     */
    public function __construct($login_id_usuario, $caminho_compilador_python, $caminho_deteccao_python, 
                                $caminho_arquivo_log, $caminho_arquivo_pickle, $caminho_arquivo_npy, 
                                $filtro_caminho_origem, $filtro_caminho_destino, $filtro_data_inicial, 
                                $filtro_data_final, $filtro_copiar_recortar, $habilitar_data, 
                                $habilitar_copiar_recortar, $nome_botao_log)
    {
        $this->login_id_usuario = $login_id_usuario;
        $this->caminho_compilador_python = $caminho_compilador_python;
        $this->caminho_deteccao_python = $caminho_deteccao_python;
        $this->caminho_arquivo_log = $caminho_arquivo_log;
        $this->caminho_arquivo_pickle = $caminho_arquivo_pickle;
        $this->caminho_arquivo_npy = $caminho_arquivo_npy;
        $this->filtro_caminho_origem = $filtro_caminho_origem;
        $this->filtro_caminho_destino = $filtro_caminho_destino;
        $this->filtro_data_inicial = $filtro_data_inicial;
        $this->filtro_data_final = $filtro_data_final;
        $this->filtro_copiar_recortar = $filtro_copiar_recortar;
        $this->habilitar_data = $habilitar_data;
        $this->habilitar_copiar_recortar = $habilitar_copiar_recortar;
        $this->nome_botao_log = $nome_botao_log;
    }

    /**
     * Execute the job.
     */
    public function handle() {
        try {
            dd('entrou handle');
            session()->forget(['log', 'error', 'debug']);

            if (session('caminhoPastaGoogleDrive') == '') {
                session()->flash('error', 'Favor informar uma pasta de origem contendo as imagens.');
                return redirect()->route('organizar');   
            } else {
                $this->baixarPasta();
                session()->put('caminhoPastaGoogleDrive',  '');
            }   

            if ($this->habilitar_data == '') {
                $this->filtro_data_inicial = date('d/m/Y', strtotime($this->filtro_data_inicial));
                $this->filtro_data_final = date('d/m/Y', strtotime($this->filtro_data_final));
            } else {
                $this->filtro_data_inicial = 'None'; 
                $this->filtro_data_final= 'None'; 
            }
            if ($this->habilitar_copiar_recortar == '') {
                $this->filtro_copiar_recortar = $this->filtro_copiar_recortar;
            } else {
                $this->filtro_copiar_recortar = 'None'; 
            }

            $parametros = [     
                '2',  // Parametro referente a rotina de treianento que será realizada no python.          
                $this->filtro_caminho_origem, 
                $this->filtro_caminho_destino, 
                $this->caminho_arquivo_log, 
                $this->caminho_arquivo_pickle, 
                $this->caminho_arquivo_npy, 
                'None', 
                $this->filtro_data_inicial, 
                $this->filtro_data_final, 
                $this->filtro_copiar_recortar, 
                'None'
            ];

            $comando = $this->caminho_compilador_python .' ' .$this->caminho_deteccao_python .' ' .implode(' ', $parametros);
            $comando = escapeshellcmd($comando);
            $cmdResult = shell_exec($comando);

            dd('saiu handle');

            $this->mostrarLogMinimizado(); 
            
            return redirect()->route('duplicidade');   
        
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "verificaDuplicidade". Erro: ' .$e->getMessage());
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('duplicidade');   
        }
    }

    // Função responsavel em mostrar a mensagem do arquivo log minimizado.
    public function mostrarLogMinimizado() {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = file($this->caminho_arquivo_log);   
                $texto_penultima_linha_log = $texto_completo_log[count($texto_completo_log) - 2];
                $this->nome_botao_log = 'Leia mais'; 
                session()->flash('log', $texto_penultima_linha_log);  
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMinimizado". Erro: ' .$e->getMessage());
            return redirect()->route('duplicidade');    
        }
    }   

    protected function getGoogleClient() {
        $cliente = new Client();
        $cliente->setAuthConfig(storage_path('app/client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
        $cliente->addScope(Drive::DRIVE);
        $cliente->setRedirectUri('http://127.0.0.1:8000/oauth-google-callback');
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $cliente->setHttpClient($guzzleClient);
        return $cliente;
    }

    public function baixarPasta() {
        try {
            
            $client = $this->getGoogleClient();

            if (!session('access_token')) {
                return redirect($client->createAuthUrl());
            }
    
            $client->setAccessToken(session('access_token'));
            $drive = new Drive($client);
    
        	$tempDir = storage_path('app\\public\\' .$this->login_id_usuario .'\\temp\\');

            // Limpeza do diretório temporário 
            if (Storage::exists($tempDir)) {
                Storage::deleteDirectory($tempDir);
            }

            if (!Storage::exists($tempDir)) {
                Storage::makeDirectory($tempDir);
            }
             
            $this->baixarArquivosRecursivamente($drive, session('caminhoPastaGoogleDrive'), $tempDir);
    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "baixarPasta". Erro: ' .$e->getMessage());
            return redirect()->route('duplicidade'); 
        }
    }
    

    public function baixarArquivosRecursivamente($drive, $pastaId, $caminho) {
        //session()->flash('log', 'Baixando arquivos da pasta: ' .$pastaId .' para o caminho: ' .$caminho);
    
        try {
            $resultados = $drive->files->listFiles([
                'q' => "'{$pastaId}' in parents",
                'fields' => 'files(id, name, mimeType)'
            ]);
  
            foreach ($resultados->getFiles() as $arquivo) {
                //session()->flash('log', 'Processando arquivo/pasta: ' .$arquivo->name .$arquivo->id); 

                if ($arquivo->mimeType == 'application/vnd.google-apps.folder') {
                    $novoCaminho = $caminho . $arquivo->name . '/';
                    
                    if (!Storage::exists($novoCaminho)) {
                        Storage::makeDirectory($novoCaminho);
                    }
    
                    // Evitar loops infinitos verificando se a pasta já foi processada
                    static $pastasProcessadas = [];
                    if (isset($pastasProcessadas[$arquivo->id])) {
                        //session()->flash('error', 'Loop detectado! Pasta ' .$arquivo->name  .$arquivo->id .' já foi processada.');
                        continue;
                    }
    
                    $pastasProcessadas[$arquivo->id] = true;
                    $this->baixarArquivosRecursivamente($drive, $arquivo->id, $novoCaminho);
                } else {
                    $conteudo = $drive->files->get($arquivo->id, ['alt' => 'media']);
                    $user = Auth::user();
                    Storage::put('\\public\\' .$this->login_id_usuario .'\\temp\\' .$arquivo->name, $conteudo->getBody()->getContents());
                    //session()->flash('log', 'Arquivo baixado: {$arquivo->name}');
                }
            }

        } catch (Exception $e) {
            session()->flash('error', 'Erro ao baixar arquivos da pasta: '.$pastaId .' .Erro: ' . $e->getMessage());
            return redirect()->route('duplicidade'); 
        }
    }

    // Dentro da função que você quer disparar o job
    public function iniciarVerificacaoDeDuplicidade() {
        VerificaDuplicidadeJob::dispatch(
            $this->login_id_usuario,
            $this->caminho_compilador_python,
            $this->caminho_deteccao_python,
            $this->caminho_arquivo_log,
            $this->caminho_arquivo_pickle,
            $this->caminho_arquivo_npy,
            $this->filtro_caminho_origem,
            $this->filtro_caminho_destino,
            $this->filtro_data_inicial,
            $this->filtro_data_final,
            $this->filtro_copiar_recortar,
            $this->habilitar_data,
            $this->habilitar_copiar_recortar,
            $this->nome_botao_log
        );
    }
    
}
