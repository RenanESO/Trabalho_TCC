<div>

    <!-- Inicio :: Main -->
    <main class="container-fluid">

        <!-- Inicio :: Formulario -->
        <div class="formulario">

            <!-- Inicio :: Tela Cinza Carregamento -->
            <div class="overlay" wire:loading wire:target="organizar"> </div>
            <!-- Fim :: Tela Cinza Carregamento -->

            <!-- Inicio :: Carregamento -->
            <div class="alert alert-primary text-center shadow-sm p-3 mt-4 rounded" wire:loading.grid wire:target="organizar">
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

            <!-- Inicio :: Titulo Card Principal-->
            <div class="card bg-body">

                <!-- Inicio :: Titulo Card Principal-->
                <div class="card-header">
                    <h3 class="text-center"> Configure a organização das fotos </h3>
                </div>
                <!-- Fim :: Titulo Card Principal-->

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

                                <div class="row">

                                    <!-- Inicio :: Botão para abrir o popup -->
                                    <div class="col-lg">                                        
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pastaDownloadModal"> Selecione a Pasta </button>                                      
                                    </div>
                                    <!-- Fim :: Botão para abrir o popup -->

                                    <!-- Inicio :: Caminho Origem -->
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
                                    
                                    <!-- Inicio :: Caminho Origem -->

                                    <!-- Fim :: Coluna Vazia -->
                                    <div class="col-lg"> 

                                    </div>
                                    <!-- Fim :: Coluna Vazia -->

                                </div>

                            </div>
                            <!-- Fim :: Conteudo Card -->

                        </div>
                        <!-- Fim :: 1º Card -->

                    </div>
                    <!-- Fim :: 1ª Coluna -->

                </div>
                <!-- Fim :: 1ª Linha -->

                <!-- Inicio :: 2ª Linha -->
                <div class="row">

                    <!-- Inicio :: 1ª Coluna -->
                    <div class="col-lg">    

                        <!-- Inicio :: 3º Card -->
                        <div class="card">

                            <!-- Inicio :: Titulo Card -->
                            <div class="card-header">
                                <h4 class="text"> 2º Passo: Configure o(s) filtro(s) </h4>
                            </div>
                            <!-- Fim :: Titulo Card -->

                            <!-- Inicio :: Conteudo Card -->
                            <div class="card-body">


                                <div class="row">


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

                                        <!-- Inicio :: Filtrar Aumentar Resolucao -->      
                                        <div class="mb-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" wire:click="alterarStatusResolucao">
                                                <label class="form-check-label" for="flexSwitchCheckDefault"> Deseja alterar a resolução da(s) foto(s) </label>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg">
                                                    <div class="input-group">
                                                        <span class="input-group-text"> Resolução da(s) foto(s) aumentada: </span>
                                                        <select class="form-select" wire:model="filtro_resolucao" {{ $habilitar_resolucao }}>
                                                            <option value="1"> 1x </option>
                                                            <option value="2"> 2x </option>
                                                            <option value="3"> 3x </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fim :: Filtrar Aumentar Resolucao -->

                                    </div>


                                </div>


                                <!-- Inicio :: Filtrar Pessoa -->
                                <br>
                                <div class="col-lg">
                                    <div class="input-group mb-3">                   
                                        <span class="input-group-text"> Buscar pelo pessoa: </span>
                                        <input class="form-control" type="search" placeholder="Nome" wire:model="query_filtro_pessoa" aria-label="Search" wire:input.debounce.300ms="reiniciarPaginacao">           
                                    </div> 
                                </div>
                                <!-- Fim :: Filtrar Pessoa -->

                                <!-- Inicio :: Lista Pessoa -->
                                <table class="table-pessoa">
                                    <tbody class="form-check">
                                        @foreach($listaPessoas as $pessoa)
                                        <tr class="list-group-item d-flex align-items-center">
                                                <td class="p-2"> 
                                                    <input class="form-check-input" type="radio" name="rgPessoa" id="rgPessoa" wire:model="filtro_pessoa_organizar" value={{ $pessoa->id }}> 
                                                </td>
                                                <td class="p-2"> 
                                                    @if ($pessoa->rostos->isNotEmpty())
                                                        <img src="{{ asset('storage/'. $pessoa->rostos[0]->url_rosto) }}" alt={{ $pessoa->nome }} width="150" height="150">
                                                    @else
                                                        <p> Sem foto disponível </p>
                                                    @endif
                                                </td>
                                                <td class="p-2"> 
                                                    <label class="form-check-label" for="rgPessoa"> {{ $pessoa->nome }} </label>
                                                </td>
                                        </tr>
                                        @endforeach 
                                    </tbody>
                                </table>
                                {{ $listaPessoas->links() }}
                                <!-- Inicio :: Lista Pessoa -->

                            </div>
                            <!-- Fim :: Conteudo Card -->

                        </div>
                        <!-- Fim :: 3º Card -->                   

                    </div>   
                    <!-- Fim :: 1ª Coluna -->

                </div>
                <!-- Fim :: 2ª Linha -->

                <!-- Inicio :: 3ª Linha -->
                <div class="row">

                    <!-- Inicio :: Botao Organizar -->
                    <form class="col-lg" wire:submit.prevent="organizar" wire:confirm="Deseja realmente organizar repositorio?">
                        <div class="d-grid gap-2 col-4 mx-auto mt-3">                  
                            <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Organizar </button>
                        </div>
                    </form>
                    <!-- Fim :: Botao Organizar -->

                </div>
                <!-- Fim :: 3ª Linha -->

            </div>
            <!-- Inicio :: Titulo Card Principal-->

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
                    <livewire:pasta-download-servidor retonarRota="organizar" />
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
</script>