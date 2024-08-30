<?php

namespace App\Livewire;

use App\Services\GoogleService;
use Livewire\Component;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class Duplicidade extends Component 
{ 
    protected $googleDriveClient;

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
    public $nome_botao_log; 

    // Função construtora da pagina no blade "Duplicidade".
    public function mount()
    {
        // Definindo a variavel com o ID Usuario que esta logado.
        $this->login_id_usuario = auth()->id();
        
        // Definindo as variaveis com os caminhos do compilador e aplicação Python.
        $this->caminho_compilador_python = 'C:\\Users\\renan\\anaconda3\\envs\\Projeto_Deteccao\\python.exe';
        $this->caminho_deteccao_python = storage_path('app\\public\\deteccao\\main.py'); 
        
        // Definindo as variaveis com os caminhos dos arquivos e diretórios.
        $this->caminho_arquivo_log = storage_path('app\\public\\' .$this->login_id_usuario .'\\log.txt');
        $this->caminho_arquivo_pickle = storage_path('app\\public\\' .$this->login_id_usuario .'\\indicesTreinamento.pickle');
        $this->caminho_arquivo_npy = storage_path('app\\public\\' .$this->login_id_usuario .'\\fotosTreinamento.npy'); 

        // Definindo as variaveis para realizar a rotina de duplicidade.
        $this->filtro_caminho_origem = storage_path('app\\public\\' .$this->login_id_usuario .'\\temp'); 
        $this->filtro_caminho_destino = storage_path('app\\public\\' .$this->login_id_usuario .'\\resultado'); 
        $this->filtro_data_inicial = now()->toDateString();
        $this->filtro_data_final = now()->toDateString();
        $this->filtro_copiar_recortar = '0';

        // Definindo as variaveis referentes aos status dos edits dos filtros.
        $this->habilitar_data = 'disabled';

        // Definindo a variavel com o nome do botão do resultado da rotina de duplicidade.
        $this->nome_botao_log = 'Leia mais';
    }

    // Função render da pagina no blade "Duplicidade".
    public function render() 
    {
        $nomeApp = "FotoPlus";   
        return view('livewire.duplicidade', compact('nomeApp'));   
    }

    // Função responsavel em mostrar a mensagem do arquivo log maximizado.
    public function mostrarLogMaximizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log))  {
                $texto_completo_log = file($this->caminho_arquivo_log);  
                $this->nome_botao_log = 'Leia menos'; 
                session()->flash('log', $texto_completo_log);
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
                    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMaximizado". Erro: ' .$e->getMessage());
            return redirect()->route('duplicidade');   
        }
    }   

    // Função responsavel em mostrar a mensagem do arquivo log minimizado.
    public function mostrarLogMinimizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = file($this->caminho_arquivo_log); 
                if (count($texto_completo_log) >= 2) {  
                    $texto_penultima_linha_log = $texto_completo_log[count($texto_completo_log) - 2];
                } else {
                    $texto_penultima_linha_log = $texto_completo_log;
                }
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

    // Função responsavel em alterar o tamanho da mensagem do arquivo log.
    public function alterarTamanhoLog() 
    {
        try {
            if ($this->nome_botao_log == 'Leia mais') {            
                $this->mostrarLogMaximizado();
            } else {          
                $this->mostrarLogMinimizado();
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarTamanhoLog". Erro: ' .$e->getMessage());
            return redirect('duplicidade');   
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

    // Função responsavel em realizar a verificação de duplicidade no conjunto de fotos  
    // selecionado, retornando as possiveis fotos iguais em outra pasta.
    public function verificaDuplicidade() 
    {
        try {
            session()->forget(['log', 'error', 'debug']);

            // Verifica se ID do usuário está definido.
            if (!$this->login_id_usuario) {
                session()->flash('error', 'Não foi encontrado o ID do usuário logado.');
                return redirect()->route('duplicidade');   
            }

            // Verifica se foi preenchido os campos de parâmetros.
            if ($this->habilitar_data == '') {
                $this->filtro_data_inicial = date('d/m/Y', strtotime($this->filtro_data_inicial));
                $this->filtro_data_final = date('d/m/Y', strtotime($this->filtro_data_final));
            } else {
                $this->filtro_data_inicial = 'None';
                $this->filtro_data_final = 'None';
            }

            // Verifica se foi peenchido o caminho da pasta com as imagens, e depois 
            // realizar o download dessas fotos.
            if (session('caminhoPastaGoogleDrive') == '') {
                session()->flash('error', 'Favor informar uma pasta de origem contendo as imagens.');
                return redirect()->route('duplicidade');
            } else {
                $googleServico = new GoogleService();
                $googleServico->baixarPasta($this->filtro_data_inicial, $this->filtro_data_final);
            }

            $parametros = [
                '2',                            // Parametro referente a rotina de treianento que será realizada no python.
                $this->filtro_caminho_origem,   // Parametro referente ao caminho de origem.
                $this->filtro_caminho_destino,  // Parametro referente ao caminho de destino.
                $this->caminho_arquivo_log,     // Parametro referente ao caminho do arquivo log gerado pelo Python.
                $this->caminho_arquivo_pickle,  // Parametro referente ao caminho do arquivo pickle.
                $this->caminho_arquivo_npy,     // Parametro referente ao caminho do arquivo npy.
                'None',                         // Parametro referente ao id da pessoa que vai realizar o treinamento do rosto.
                $this->filtro_data_inicial,     // Parametro referente a data inicial do conjunto das fotos. 
                $this->filtro_data_final,       // Parametro referente a data final do conjunto das fotos. 
                $this->filtro_copiar_recortar,  // Parametro referente se as fotos devem ser copiadas ou recortadas.
                'None'                          // Parametro referente quanto deve aumentar resolução das imagens.
            ];

            // Chamada externa do python para realizar a organização das fotos referente 
            // aos filtros selecionados.
            $comando = $this->caminho_compilador_python .' ' .$this->caminho_deteccao_python .' ' .implode(' ', $parametros);
            session()->flash('debug', 'Comando: ' .$comando); 
            
            $comando = escapeshellcmd($comando);
            $cmdResult = shell_exec($comando);

            // Mostra o conteudo do arquivo log minimizado.
            $this->mostrarLogMinimizado();

            $this->criarZip();

            // Redireciona para a rota de download
            return redirect()->route('download-zip', ['user_id' => $this->login_id_usuario]); 

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "verificaDuplicidade". Erro: ' .$e->getMessage());
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('duplicidade');
        }
    }  

    public function criarZip()
    {
        $folderPath = $this->filtro_caminho_destino; 
        $zipFilePath = storage_path('app\\public\\' . $this->login_id_usuario . '\\resultado.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $relativePath = substr($file->getRealPath(), strlen($folderPath) + 1);                  
                    $zip->addFile($file->getRealPath(), $relativePath);
                }
            }
            $zip->close();
        } else {
            session()->flash('error', 'Não foi possível criar o arquivo ZIP');
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('organizar');
        }
    }

}


