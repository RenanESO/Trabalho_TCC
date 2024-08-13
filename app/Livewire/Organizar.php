<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Drive;
use App\Models\Pessoa;
use Exception;

class Organizar extends Component {

    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $login_id_usuario;
    public $caminho_compilador_python;
    public $caminho_deteccao_python;
    public $caminho_arquivo_log;
    public $caminho_arquivo_pickle;
    public $caminho_arquivo_npy;
    public $query_filtro_pessoa; 
    public $filtro_caminho_origem;
    public $filtro_caminho_destino;
    public $filtro_pessoa_organizar; 
    public $filtro_data_inicial;
    public $filtro_data_final;
    public $filtro_copiar_recortar;
    public $filtro_resolucao;
    public $habilitar_data; 
    public $habilitar_copiar_recortar;
    public $habilitar_resolucao;
    public $nome_botao_log; 

    // Função construtora da pagina no blade "Treinamento".
    public function mount() {    
        // Definindo a variavel com o ID Usuario que esta logado.
        $this->login_id_usuario = auth()->id(); 

        // Definindo as variaveis com os caminhos do compilador e aplicação Python.
        $this->caminho_compilador_python = 'C:\\Users\\renan\\anaconda3\\envs\\Projeto_Deteccao\\python.exe';
        $this->caminho_deteccao_python = storage_path('app\\public\\deteccao\\main.py'); 
        
        // Definindo as variaveis com os caminhos dos arquivos e diretórios.
        $this->caminho_arquivo_log = storage_path('app\\public\\' .$this->login_id_usuario .'\\log.txt');
        $this->caminho_arquivo_pickle = storage_path('app\\public\\' .$this->login_id_usuario .'\\indicesTreinamento.pickle');
        $this->caminho_arquivo_npy = storage_path('app\\public\\' .$this->login_id_usuario .'\\fotosTreinamento.npy');

        // Definindo variavel de filtro da query de pessoas.
        $this->query_filtro_pessoa = '';

        // Definindo as variaveis dos filtro para realizar a rotina de organizar.
        $this->filtro_caminho_origem = storage_path('app\\public\\' .$this->login_id_usuario .'\\temp'); // Local da pasta no GoogleDrive.
        $this->filtro_caminho_destino = storage_path('app\\public\\' .$this->login_id_usuario .'\\resultado'); // Local da pasta no GoogleDrive.
        $this->filtro_data_inicial = now()->toDateString();
        $this->filtro_data_final = now()->toDateString();
        $this->filtro_pessoa_organizar = '';
        $this->filtro_copiar_recortar = '';
        $this->filtro_resolucao = '';

        // Definindo as variaveis referentes aos status dos edits dos filtros.
        $this->habilitar_data = 'disabled';
        $this->habilitar_copiar_recortar = 'disabled';
        $this->habilitar_resolucao = 'disabled';

        // Definindo a variavel com o nome do botão do resultado da rotina de organizar.
        $this->nome_botao_log = 'Leia mais';
    }

    public function render() {
        $nomeApp = "FotoPlus"; 
        $user = auth()->id(); 
    
        $listaPessoas = Pessoa::select('pessoas.*', DB::raw('MIN(rostos.url_rosto) as url_rosto'))
            ->leftJoin('rostos', 'rostos.id_pessoa', '=', 'pessoas.id')
            ->where('pessoas.user_id', $user) 
            ->where('pessoas.nome', 'like', '%' .$this->query_filtro_pessoa .'%')
            ->groupBy('pessoas.id')
            ->orderBy('pessoas.nome')
            ->paginate(10);
    
        return view('livewire.organizar', compact('nomeApp', 'listaPessoas'));
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
            return redirect()->route('organizar'); 
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
            return redirect()->route('organizar'); 
        }
    }

    // Função responsavel em mostrar a mensagem do arquivo log maximizado.
    public function mostrarLogMaximizado() {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = implode(' | ', file($this->caminho_arquivo_log));
                $this->nome_botao_log = 'Leia menos'; 
                session()->flash('log', $texto_completo_log);
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
                    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMaximizado". Erro: ' .$e->getMessage());
            return redirect()->route('organizar'); 
        }
    }   

    //Função responsavel em mostrar a mensagem do arquivo log minimizado.
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
            return redirect()->route('organizar');   
        }
    }   

    //Função responsavel em alterar o tamanho da mensagem do arquivo log.
    public function alterarTamanhoLog() {
        try {
            if ($this->nome_botao_log == 'Leia mais') {            
                $this->mostrarLogMaximizado();
            } else {          
                $this->mostrarLogMinimizado();
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarTamanhoLog". Erro: ' .$e->getMessage());
            return redirect()->route('organizar'); 
        }
    }   

    // Função responsavel em reiniciar a paginação para a primeira página.
    public function reiniciarPaginacao() {
        try {
            $this->resetPage();

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "reiniciarPaginacao". Erro: ' .$e->getMessage());
            return redirect()->route('organizar'); 
        }
    }   

    // Função responsavel em alterar o status do campo "Data" 
    // para habilitar|desabilitar o edit.
    public function alterarStatusData() {
        try {
            if ($this->habilitar_data == '') {
                $this->habilitar_data = 'disabled';    
            } else {
                $this->habilitar_data = '';     
            }

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarStatusData". Erro: ' .$e->getMessage());
            return redirect()->route('organizar');   
        }
    }    

    // Função responsavel em alterar o status do campo "CopiarColar" 
    // para habilitar|desabilitar o checkbox.
    public function alterarStatusCopiarColar() {
        try {
            if ($this->habilitar_copiar_recortar == '') {
                $this->habilitar_copiar_recortar = 'disabled';    
            } else {
                $this->habilitar_copiar_recortar = '';     
            }

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarStatusCopiarColar". Erro: ' .$e->getMessage());
            return redirect()->route('organizar');  
        }
    }  

    // Função responsavel em alterar o status do campo "Resolução da Imagem" 
    // para habilitar|desabilitar o combobox.
    public function alterarStatusResolucao() {
        try {
            if ($this->habilitar_resolucao == '') {
                $this->habilitar_resolucao = 'disabled';    
            } else {
                $this->habilitar_resolucao = '';     
            }

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarStatusResolucao". Erro: ' .$e->getMessage());
            return redirect()->route('organizar'); 
        }
    }  

    // Função responsavel em realizar a verificação e organização do conjunto de fotos  
    // selecionado, retornando o resultado em outra pasta.
    public function organizar() {
        try {
            // Limpa a mensagem flash antes de executar a rotina
            session()->forget(['log', 'error', 'debug']);

            // Verifica se foi peenchido o caminho da pasta com as imagens, e depois 
            // realizar o download dessas fotos.
            if (session('caminhoPastaGoogleDrive') == '') {
                session()->flash('error', 'Favor informar uma pasta de origem contendo as imagens.');
                return redirect()->route('organizar');   
            } else {
                $this->baixarPasta();
                session()->put('caminhoPastaGoogleDrive',  '');
            }      

            if ($this->filtro_pessoa_organizar == '') {
                session()->flash('error', 'Favor informar uma pessoa para organizar as imagens.');
                return redirect()->route('organizar');   
            }

            // Verifica se foi preenchido os campos de parametros.
            if ($this->habilitar_data == '') {
                $this->filtro_data_inicial = date('d/m/Y', strtotime($this->filtro_data_inicial));
                $this->filtro_data_final = date('d/m/Y', strtotime($this->filtro_data_final));
            } else {
                $this->filtro_data_inicial = 'None'; 
                $this->filtro_data_final = 'None'; 
            }
            if ($this->habilitar_copiar_recortar == '') {
                $this->filtro_copiar_recortar = $this->filtro_copiar_recortar;
            } else {
                $this->filtro_copiar_recortar = 'None'; 
            }
            if ($this->habilitar_resolucao == '') {
                $this->filtro_resolucao = $this->filtro_resolucao;
            } else {
                $this->filtro_resolucao = 'None'; 
            }

            $parametros = [     
                '1',  // Parametro referente a rotina de treianento que será realizada no python.          
                $this->filtro_caminho_origem, // Parametro referente ao caminho de origem.
                $this->filtro_caminho_destino, // Parametro referente ao caminho de destino.
                $this->caminho_arquivo_log, // Parametro referente ao caminho do arquivo log gerado pelo Python.
                $this->caminho_arquivo_pickle, // Parametro referente ao caminho do arquivo pickle.
                $this->caminho_arquivo_npy, // Parametro referente ao caminho do arquivo npy.
                $this->filtro_pessoa_organizar, // Parametro referente ao id da pessoa que vai realizar o treinamento do rosto.
                $this->filtro_data_inicial, // Parametro referente a data inicial do conjunto das fotos. 
                $this->filtro_data_final, // Parametro referente a data final do conjunto das fotos. 
                $this->filtro_copiar_recortar, // Parametro referente se as fotos devem ser copiadas ou recortadas.
                $this->filtro_resolucao // Parametro referente quanto deve aumentar resolução das imagens.
            ];

            // Chamada externa do python para realizar a organização das fotos 
            // referente aos filtros selecionados.
            $comando = $this->caminho_compilador_python .' ' .$this->caminho_deteccao_python .' ' .implode(' ', $parametros);           
            //session()->flash('debug', 'Comando: ' .$comando);  

            $comando = escapeshellcmd($comando);
            $cmdResult = shell_exec($comando);

            // Mostra o conteudo do arquivo log minimizado.
            $this->mostrarLogMinimizado();

            return redirect()->route('organizar');   
        
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "organizar". Erro: ' .$e->getMessage());
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('organizar');       
        }
    }  
}
