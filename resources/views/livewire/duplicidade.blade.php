<div>
    
    <!-- Inicio :: Main -->
    <main class="container-fluid">

        <!-- Inicio :: Formulario -->
        <div class="formulario">

            <!-- Inicio :: Tela Cinza Carregamento -->
            <div class="overlay" wire:loading wire:target="verificaDuplicidade"> </div>
            <!-- Fim :: Tela Cinza Carregamento -->

            <!-- Inicio :: Carregamento -->
            <div class="alert alert-primary text-center shadow-sm p-3 mt-4 rounded" wire:loading.grid wire:target="verificaDuplicidade">
                <i class="fas fa-spinner fa-spin"></i> <span class="alert-text"> Aguarde Carregando... </span>
            </div>
            <!-- Fim :: Carregamento -->

            <!-- Inicio :: Alerta -->
            @if (session('log'))
                <div class="alert alert-light text-center shadow-sm p-3 mt-4 rounded">
                    <i class="fa fa-cog"></i> <span class="alert-text"> {{ session('log') }} </span> <br><br>
                    <button type="button" class="btn btn-secondary" wire:click="alterarTamanhoLog"> {{ $nome_botao_log }} </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center shadow-sm p-3 mt-4 rounded">
                    <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('error') }} </span>
                </div>
            @endif
            @if (session('debug'))
                <div class="alert alert-primary text-center shadow-sm p-3 mt-4 rounded">
                    <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('debug') }} </span>
                </div>
            @endif
            <!-- Fim :: Alerta -->

            <!-- Inicio :: Titulo Card Principal -->
            <div class="card bg-body">

                <!-- Inicio :: Titulo Card Principal -->
                <div class="card-header">
                    <h3 class="text-center"> Configure a verificação de duplicidade das fotos </h3>
                </div>
                <!-- Fim :: Titulo Card Principal b-->

                <!-- Inicio :: Conteudo Card -->
                <div class="card-body">

                <!-- Inicio :: 1ª Linha -->
                <div class="row">

                    <!-- Inicio :: 1ª Coluna -->
                    <div class="col-lg mb-3"> 
                        
                        <!-- Inicio :: 1º Card -->
                        <div class="card">

                            <!-- Inicio :: Titulo Card -->
                            <div class="card-header">
                                <h4 class="text"> 1º Passo: Definir local de origem do conjunto das fotos </h4>
                            </div>
                            <!-- Fim :: Titulo Card -->

                            <!-- Inicio :: Conteudo Card -->
                            <div class="card-body">

                                <!-- Inicio :: Caminho Origem 
                                <form wire:submit.prevent="uploadFotos">
                                    <input type="file" wire:model="filtro_caminho_origem" required multiple>
                                </form>
                                 Fim :: Caminho Origem -->

                                <!-- Inicio :: Caminho Destino -->
                                <div class="input-group mb-3">
                                    <input type="file" wire:model="filtro_caminho_origem" required>
                                </div>
                                <!-- Fim :: Caminho Destino -->

                            </div>
                            <!-- Fim :: Conteudo Card -->

                        </div>
                        <!-- Fim :: 1º Card -->

                    </div>
                    <!-- Fim :: 1ª Coluna -->

                    <!-- Inicio :: 2ª Coluna -->
                    <div class="col-lg mb-3">  

                        <!-- Inicio :: 2º Card -->
                        <div class="card">

                            <!-- Inicio :: Titulo Card -->
                            <div class="card-header">
                                <h4 class="text"> 2º Passo: Definir local de destino do conjunto das fotos </h4>
                            </div>
                            <!-- Fim :: Titulo Card -->

                            <!-- Inicio :: Conteudo Card -->
                            <div class="card-body">

                                <!-- Inicio :: Caminho Destino -->
                                <div class="input-group mb-3">
                                    <input type="file" wire:model="filtro_caminho_destino" required>
                                </div>
                                <!-- Fim :: Caminho Destino -->

                            </div>
                            <!-- Fim :: Conteudo Card -->

                        </div>
                        <!-- Fim :: 2º Card -->

                    </div>
                    <!-- Fim :: 2ª Coluna -->
                        
                </div>
                <!-- Fim :: 1ª Linha -->

                <!-- Inicio :: 2ª Linha -->
                <div class="row">

                    <!-- Inicio :: 1ª Coluna -->
                    <div class="col-lg">    

                        <!-- Inicio :: 2º Card -->
                        <div class="card">

                            <!-- Inicio :: Titulo Card -->
                            <div class="card-header">
                                <h4 class="text"> 3º Passo: Configure o(s) filtro(s) </h4>
                            </div>
                            <!-- Fim :: Titulo Card -->

                            <!-- Inicio :: Conteudo Card -->
                            <div class="card-body">


                                <div class="col-md-7">

                                    <!-- Inicio :: Filtrar Data -->      
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:click="alterarStatusData">
                                            <label class="form-check-label" for="flexSwitchCheckDefault"> Deseja organizar a(s) foto(s) por data </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="input-group">
                                                    <span class="input-group-text"> Periodo de Data: </span>
                                                    <input class="form-control date-mask" type="date" placeholder="Data Inicial" wire:model="filtro_data_inicial" {{ $habilitar_data }}>
                                                    <input class="form-control date-mask" type="date" placeholder="Data Final" wire:model="filtro_data_final" {{ $habilitar_data }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim :: Filtrar Data -->

                                    <!-- Inicio :: Filtrar Copiar|Recortar -->    
                                    <div class="mb-4">  
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:click="alterarStatusCopiarColar">
                                            <label class="form-check-label" for="flexSwitchCheckDefault"> Deseja copiar ou recortar a(s) foto(s) </label>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <div class="input-group">
                                                    <span class="input-group-text"> Copiar ou recortar a(s) foto(s): </span>
                                                    <select class="form-select" wire:model="filtro_copiar_recortar" {{ $habilitar_copiar_recortar }}>
                                                        <option value="0"> Copiar </option>
                                                        <option value="1"> Recortar </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim :: Filtrar Copiar|Recortar -->

                                </div>

                            </div>
                            <!-- Fim :: Conteudo Card -->

                        </div>
                        <!-- Fim :: 2º Card -->                   

                    </div>   
                    <!-- Fim :: 1ª Coluna -->

                </div>
                <!-- Fim :: 2ª Linha -->

                <!-- Inicio :: 3ª Linha -->
                <div class="row">

                    <!-- Inicio :: 1ª Coluna -->
                    <div class="col-lg">  

                        <!-- Inicio :: Botao Verificar Duplicidade -->
                        <form wire:submit.prevent="verificaDuplicidade" wire:confirm="Deseja realmente continuar?">
                            <div class="d-grid gap-2 col-4 mx-auto mt-3">                  
                                <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Verificar </button>
                            </div>
                        </form>
                        <!-- Fim :: Botao Verificar Duplicidade -->

                    </div>
                    <!-- Fim :: 1ª Coluna -->

                </div>
                <!-- Fim :: 3ª Linha -->

            </div>
            <!-- Fim :: Titulo Card Principal-->

        </div>
        <!-- Fim :: Formulario -->

    </main>
    <!-- Fim :: Main -->

</div>
