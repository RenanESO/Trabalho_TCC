<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pessoa;
use App\Models\Rosto;
use Exception;

class Treinamento extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $login_id_usuario;
    public $caminho_compilador_python;
    public $caminho_deteccao_python;
    public $caminho_arquivo_log;
    public $caminho_arquivo_pickle;
    public $caminho_arquivo_npy;
    public $filtro_caminho_origem; 
    public $nome_pessoa_cadastro;
    public $id_pessoa_treinamento;
    public $image_pessoa_treinamento;
    public $query_pessoas_cadastro;
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

        // Definindo as variaveis para realizar a rotina de treinamento.
        $this->filtro_caminho_origem = '';
        $this->nome_pessoa_cadastro = '';
        $this->id_pessoa_treinamento = '';
        $this->image_pessoa_treinamento = null;
        $this->query_pessoas_cadastro = '';

        // Definindo a variavel com o nome do botão do resultado da rotina de treinamento.
        $this->nome_botao_log = 'Leia mais';
    }

    // Função principal para renderizar a pagina no blade "Treinamento".
    public function render() {
        $nomeApp = "FotoPlus";  
        $listaPessoas = Pessoa::select(['pessoas.*', 'rostos.url_rosto'])
            ->leftJoin('rostos', function($join) {
                $join->on('rostos.id_pessoa', '=', 'pessoas.id')
                     ->whereRaw('rostos.id = (select id from rostos where rostos.id_pessoa = pessoas.id limit 1)');
            })
            ->where('nome', 'like', '%' . $this->query_pessoas_cadastro . '%')
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.treinamento', compact('nomeApp', 'listaPessoas'));
    }

    // Função responsavel em mostrar a mensagem do arquivo log maximizado.
    public function mostrarLogMaximizado() {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = implode('\n', file($this->caminho_arquivo_log));
                $this->nome_botao_log = 'Leia menos'; 
                session()->flash('log', $texto_completo_log);
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
                    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento'); 
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
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        }
    }   

    // Função responsavel em alterar o tamanho da mensagem do arquivo log.
    public function alterarTamanhoLog() {
        try {
            if ($this->nome_botao_log == 'Leia mais') {            
                $this->mostrarLogMaximizado();
            } else {          
                $this->mostrarLogMinimizado();
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        }
    } 

    // Função responsavel em reiniciar a paginação para a primeira página.
    public function reiniciarPaginacao() {
        try {
            $this->resetPage();

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        }
    }   

    // Função que abre o explorador de arquivo para buscar de uma imagem de rosto dentro da maquina do usuario.
    public function buscarImagem() {
        try {
            // Adapte as regras de validação conforme necessário.
            $this->validate([        
                'image_pessoa_treinamento' => 'required|image|mimes:jpeg,png,jpg,gif|min:1|max:2048'
            ]);

            // Salve a imagem na pasta de uploads ou armazene-a no seu sistema, conforme necessário.
            $this->image_pessoa_treinamento->store('uploads');
            //session()->flash('debug', 'Imagem Carregada: ' .$this->image_pessoa_treinamento);

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        }
    }

    // Função que realiza o cadastro de uma nova pessoa e rosto referente a pessoa cadastrada. 
    public function cadastrarPessoa() {
        try {
            // Limpa a mensagem flash antes de executar a rotina
            session()->forget(['log', 'error', 'debug']);

            // Adapte as regras de validação conforme necessário.
            $this->validate([
                'nome_pessoa_cadastro' => 'required|string|min:1|max:100',
                'image_pessoa_treinamento' => 'required|image|mimes:jpeg,png,jpg,gif|min:1|max:2048'
            ]);

            // Cadastrado uma nova pessoa na tabela.
            $pessoa_cadastrada = Pessoa::create(['nome' => $this->nome_pessoa_cadastro]);

            // Atribui na variavel o id da nova pessoa cadastrada para realizar o treinamento 
            // do rosto inserido.
            $this->id_pessoa_treinamento = $pessoa_cadastrada->id;

            // Reaaliza o treinamento do rosto da nova pessoa cadastrada.
            $this->treinarPessoa();

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        } 
    }

    // Função que realiza o cadastro do novo rosto da pessoa seleciona e realiza um novo treinamento.
    public function treinarPessoa() {
        try {
            // Limpa a mensagem flash antes de executar a rotina
            session()->forget(['log', 'error', 'debug']);

            // Adapte as regras de validação conforme necessário.
            $this->validate([
                'id_pessoa_treinamento' => 'required',
                'image_pessoa_treinamento' => 'required|image|mimes:jpeg,png,jpg,gif|min:1|max:2048'
            ]);

            // Defina o caminho para armazenar a imagem
            $caminho = $this->login_id_usuario .'\\' .'rostosCadastrados' .'\\' .$this->id_pessoa_treinamento;

            $this->image_pessoa_treinamento = $this->image_pessoa_treinamento->store($caminho, 'public');
            $this->image_pessoa_treinamento = str_replace('/', '\\', $this->image_pessoa_treinamento);
            $this->image_pessoa_treinamento = str_replace('public\\', '', $this->image_pessoa_treinamento);
            //session()->flash('debug', 'Caminho imagem rosto banco de dados: ' .$this->image_pessoa_treinamento);  
            
            $this->filtro_caminho_origem = storage_path('app' .'\\' .'public' .'\\' . $this->image_pessoa_treinamento);       
            //session()->flash('debug', 'Caminho imagem rosto storage: ' .$this->filtro_caminho_origem);  
    
            // Cadastrado um novo rosto na tabela, referente a pessoa criada ou selecionada.
            Rosto::create([
                'id_pessoa' => $this->id_pessoa_treinamento,
                'url_rosto' => $this->image_pessoa_treinamento,
            ]);
            
            $parametros = [     
                '0',  // Parametro referente a rotina de treianento que será realizada no python.          
                $this->filtro_caminho_origem, // Parametro referente ao caminho de origem.
                'None', // Parametro referente ao caminho de destino.
                $this->caminho_arquivo_log, // Parametro referente ao caminho do arquivo log gerado pelo Python.
                $this->caminho_arquivo_pickle, // Parametro referente ao caminho do arquivo pickle.
                $this->caminho_arquivo_npy, // Parametro referente ao caminho do arquivo npy.
                $this->id_pessoa_treinamento, // Parametro referente ao id da pessoa que vai realizar o treinamento do rosto.
                'None', // Parametro referente a data inicial do conjunto das fotos. 
                'None', // Parametro referente a data final do conjunto das fotos. 
                'None', // Parametro referente se as fotos devem ser copiadas ou recortadas.
                'None' // Parametro referente quanto deve aumentar resolução das imagens.
            ];

            // Chamada externa do python para realizar o treinamento da foto 
            // da pessoa selecionada.
            $comando = $this->caminho_compilador_python .' ' .$this->caminho_deteccao_python .' ' .implode(' ', $parametros);           
            //session()->flash('debug', 'Comando: ' .$comando);

            $comando = escapeshellcmd($comando);
            $cmdResulto = shell_exec($comando);

            // Mostra o conteudo do arquivo log minimizado.
            $this->mostrarLogMinimizado();         
               
            return redirect()->route('treinamento');    

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno. Erro: ' . $e->getMessage());
            return redirect()->route('treinamento');
        } 
    }
}
