<?php

namespace App\Livewire;

use Livewire\Component;
use Exception;

class Duplicidade extends Component { 
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

    // Função construtora da pagina no blade "Duplicidade".
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

        // Definindo as variaveis para realizar a rotina de duplicidade.
        $this->filtro_caminho_origem = storage_path('app\\public\\' .$this->login_id_usuario .'\\temp'); // Local da pasta no GoogleDrive.
        $this->filtro_caminho_destino = storage_path('app\\public\\' .$this->login_id_usuario .'\\resultado'); // Local da pasta no GoogleDrive.
        $this->filtro_data_inicial = now()->toDateString();
        $this->filtro_data_final = now()->toDateString();
        $this->filtro_copiar_recortar = '';

        // Definindo as variaveis referentes aos status dos edits dos filtros.
        $this->habilitar_data = 'disabled';
        $this->habilitar_copiar_recortar = 'disabled';

        // Definindo a variavel com o nome do botão do resultado da rotina de duplicidade.
        $this->nome_botao_log = 'Leia mais';
    }

    public function render() {
        $nomeApp = "FotoPlus";      
        return view('livewire.duplicidade', [
            'nomeApp' => $nomeApp
        ]);
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

    // Função responsavel em alterar o tamanho da mensagem do arquivo log.
    public function alterarTamanhoLog() {
        try {
            if ($this->nome_botao_log == 'Leia mais') {            
                $this->mostrarLogMaximizado();
            } else {          
                $this->mostrarLogMinimizado();
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarTamanhoLog". Erro: ' .$e);
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
            return redirect()->route('duplicidade');   
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
            return redirect()->route('duplicidade');    
        }
    }  

    // Função responsavel em realizar a verificação de duplicidade no conjunto de fotos  
    // selecionado, retornando as possiveis fotos iguais em outra pasta.
    public function verificaDuplicidade() {
        // Despachar o Job para a fila
        //dd('entrou');
        \App\Jobs\VerificaDuplicidadeJob::dispatch(
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
        dd('saiu');
    }  
}


