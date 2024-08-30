<div>
    
    <!-- Inicio :: Main -->
    <main class="container-fluid">

        <!-- Inicio :: Formulario -->
        <div class="formulario">

            <!-- Inicio :: Tela Cinza Carregamento -->
            <div class="overlay" wire:loading wire:target="verificaDuplicidade"> </div>
            <!-- Fim :: Tela Cinza Carregamento -->

            <!-- Inicio :: Card Principal -->
            <div class="card bg-body">

                <!-- Inicio :: Titulo - Card Principal -->
                <div class="card-header">
                    <h3 class="text-center"> Configure a verificação de duplicidade das fotos </h3>
                </div>
                <!-- Fim :: Titulo - Card Principal -->

                <!-- Inicio :: Conteudo - Card Principal -->
                <div class="card-body">

                    <!-- Inicio :: Tela Cinza Carregamento -->
                    <div class="overlay" wire:loading wire:target="verificaDuplicidade, alterarTamanhoLog, alterarStatusData"> </div>
                    <!-- Fim :: Tela Cinza Carregamento -->

                    <!-- Inicio :: Carregamento -->
                    <div class="alert alert-primary text-center shadow-sm p-3 mx-3 mb-3 rounded" wire:loading.grid wire:target="verificaDuplicidade, alterarTamanhoLog, alterarStatusData">
                        <i class="fas fa-spinner fa-spin"></i> <span class="alert-text"> Aguarde Carregando... </span>
                    </div>
                    <!-- Fim :: Carregamento -->

                    <!-- Inicio :: Alerta -->
                    @if (session('log'))
                        <div class="alert alert-light text-center shadow-sm p-3 mx-3 mb-3 rounded">
                            <i class="fa fa-cog"></i> <span class="alert-text"> {{ session('log') }} </span> <br><br>
                            <button type="button" class="btn btn-secondary" wire:click="alterarTamanhoLog"> {{ $nome_botao_log }} </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger text-center shadow-sm p-3 mx-3 mb-3 rounded">
                            <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('error') }} </span>
                        </div>
                    @endif
                    @if (session('debug'))
                        <div class="alert alert-primary text-center shadow-sm p-3 mx-3 mb-3 rounded">
                            <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('debug') }} </span>
                        </div>
                    @endif
                    <!-- Fim :: Alerta -->

                    <!-- Inicio :: 1ª Linha - Card Principal -->
                    <div class="row mb-3">

                        <!-- Inicio :: 1ª Coluna - Card Principal -->
                        <div class="col-lg"> 

                            <!-- Inicio :: Card 1º Passo -->
                            <div class="card">

                                <!-- Inicio :: Titulo - Card 1º Passo -->
                                <div class="card-header">
                                    <h4 class="text"> 1º Passo: Definir local de origem do conjunto das fotos </h4>
                                </div>
                                <!-- Fim :: Titulo - Card 1º Passo -->

                                <!-- Inicio :: Conteudo - Card 1º Passo -->
                                <div class="card-body">

                                    <!-- Inicio :: 1ª Linha - Card 1º Passo -->
                                    <div class="row">

                                        <!-- Inicio :: 1ª Coluna - Card 1º Passo -->
                                        <div class="col-lg">                                        
                                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#pastaDownloadModal"> Selecione a Pasta </button>                                      
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 1º Passo -->

                                        <!-- Inicio :: 2ª Coluna - Card 1º Passo -->
                                        <div class="col-lg">  
                                            @if(session('caminhoPastaGoogleDrive'))
                                                <div class="alert alert-primary text-center rounded">
                                                    <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> Pasta selecionada </span>
                                                </div>
                                            @else
                                                <div class="alert alert-danger text-center rounded">
                                                    <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> Nenhuma pasta selecionada </span>
                                                </div>                                
                                            @endif                                                          
                                        </div>             
                                        <!-- Fim :: 2ª Coluna - Card 1º Passo -->

                                        <!-- Inicio :: 3ª Coluna - Card 1º Passo -->
                                        <div class="col-lg">         

                                        </div>
                                        <!-- Fim :: 3ª Coluna - Card 1º Passo -->

                                    </div>
                                    <!-- Fim :: 1ª Linha - Card 1º Passo -->

                                </div>
                                <!-- Fim :: Conteudo - Card 1º Passo -->

                            </div>
                            <!-- Fim :: Card 1º Passo -->

                        </div>
                        <!-- Fim :: 1ª Coluna - Card Principal -->

                    </div>
                    <!-- Fim :: 1ª Linha - Card Principal -->

                    <!-- Inicio :: 2ª Linha - Card Principal -->
                    <div class="row mb-3">

                        <!-- Inicio :: 1ª Coluna - Card Principal -->
                        <div class="col-lg">    

                            <!-- Inicio :: Card 2º Passo -->
                            <div class="card">

                                <!-- Inicio :: Titulo - Card 2º Passo -->
                                <div class="card-header">
                                    <h4 class="text"> 2º Passo: Configure o(s) filtro(s) </h4>
                                </div>
                                <!-- Fim :: Titulo - Card 2º Passo -->

                                <!-- Inicio :: Conteudo - Card 2º Passo -->
                                <div class="card-body">

                                    <!-- Inicio :: 1ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">

                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->
                                        <div class="col-md-7">    
                                            <div class="row">
                                                <div class="col-lg">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" wire:click="alterarStatusData">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"> Deseja organizar a(s) foto(s) por data </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg">
                                                    <div class="input-group">
                                                        <span class="input-group-text"> Periodo de Data: </span>
                                                        <input id="filtroDataStart" class="form-control date-mask" type="date" placeholder="Data Inicial" wire:model="filtro_data_inicial" {{ $habilitar_data }}>
                                                        <input id="filtroDataEnd" class="form-control date-mask" type="date" placeholder="Data Final" wire:model="filtro_data_final" {{ $habilitar_data }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->

                                    </div>
                                    <!-- Fim :: 1ª Linha - Card 2º Passo -->

                                    <!-- Inicio :: 2ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">

                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <span class="input-group-text"> Copiar ou recortar a(s) foto(s): </span>
                                                <select id="filtroCopiarRecortar" class="form-select" wire:model="filtro_copiar_recortar">
                                                    <option value="0"> Copiar </option>
                                                    <option value="1"> Recortar </option>
                                                </select>
                                            </div>                                    
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->

                                    </div>
                                    <!-- Fim :: 2ª Linha - Card 2º Passo -->

                                </div>
                                <!-- Fim :: Conteudo - Card 2º Passo -->

                            </div>
                            <!-- Fim :: 2º Card -->                   

                        </div>   
                        <!-- Fim :: 1ª Coluna - Card Principal -->

                    </div>
                    <!-- Fim :: 2ª Linha - Card Principal -->

                    <!-- Inicio :: 3ª Linha - Card Principal -->
                    <div class="row mb-3">

                        <!-- Inicio :: 1ª Coluna - Card Principal -->
                        <div class="col-lg">  
                            <form wire:submit.prevent="verificaDuplicidade" wire:confirm="Deseja realmente continuar?">
                                <div class="d-grid gap-2 col-4 mx-auto mt-3">                  
                                    <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Verificar </button>
                                </div>
                            </form>
                        </div>
                        <!-- Fim :: 1ª Coluna - Card Principal -->

                    </div>
                    <!-- Fim :: 3ª Linha - Card Principal -->

                </div>
                <!-- Fim :: Conteudo - Card Principal -->

            </div>
            <!-- Fim :: Card Principal -->

        </div>
        <!-- Fim :: Formulario -->

    </main>
    <!-- Fim :: Main -->

    <!-- Inicio :: Modal Pasta Download -->
    <div class="modal fade" id="pastaDownloadModal" tabindex="-1" aria-labelledby="pastaDownloadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="pastaDownloadModalLabel"> Selecione a Pasta </h5>
                </div>

                <div class="modal-body">
                    <!-- Include o conteúdo do Blade pasta-download-servidor.blade aqui -->                 
                    <livewire:pasta-download-servidor retonarRota="duplicidade" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Fechar </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fim :: Modal Pasta Download -->

</div>

<script>
    function voltaInicio() {
        var element = document.getElementById("inicio");
        if (element) {
            element.scrollIntoView({ behavior: "smooth" });
        }
    }
    function toggleField(...fieldIds) {
        const isChecked = fieldIds.pop(); // O último parâmetro é o estado do checkbox
        fieldIds.forEach(id => {
            if (id) {
                const element = document.getElementById(id);
                if (element) {
                    element.disabled = !isChecked;
                }
            }
        });
    }
</script>
