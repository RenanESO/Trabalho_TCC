<div>

    <!-- Inicio :: Main -->
    <main class="container-fluid">

        <!-- Inicio :: Formulario -->
        <div class="formulario">

            <!-- Inicio :: Card Principal -->
            <div class="card bg-body">

                <!-- Inicio :: Titulo - Card Principal -->
                <div class="card-header">
                    <h3 class="text-center"> Configure a organização das fotos </h3>       
                </div>
                <!-- Fim :: Titulo - Card Principal -->

                <!-- Inicio :: Conteudo - Card Principal -->
                <div class="card-body">

                    <!-- Inicio :: Tela Cinza Carregamento -->
                    <div class="overlay" wire:loading wire:target="organizar, selecionarPessoa, alterarTamanhoLog"> </div>
                    <!-- Fim :: Tela Cinza Carregamento -->

                    <!-- Inicio :: Carregamento -->
                    <div class="alert alert-primary text-center shadow-sm p-3 mx-3 mb-3 rounded" wire:loading.grid wire:target="organizar, selecionarPessoa, alterarTamanhoLog">
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
                                                        <input class="form-check-input" type="checkbox" wire:model="habilitar_data" onclick="toggleField('filtroDataStart', 'filtroDataEnd', this.checked)">
                                                        <label class="form-check-label" for="flexSwitchCheckDefault"> Deseja organizar a(s) foto(s) por data </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg">
                                                    <div class="input-group">
                                                        <span class="input-group-text"> Periodo de Data: </span>
                                                        <input id="filtroDataStart" class="form-control date-mask" type="date" placeholder="Data Inicial" wire:model="filtro_data_inicial" disabled>
                                                        <input id="filtroDataEnd" class="form-control date-mask" type="date" placeholder="Data Final" wire:model="filtro_data_final" disabled>
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

                                    <!-- Inicio :: 3ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">

                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->
                                        <div class="col-md-7">    
                                            <div class="input-group">
                                                <span class="input-group-text"> Resolução da(s) foto(s) aumentada: </span>
                                                <select id="filtroResolucao" class="form-select" wire:model="filtro_resolucao">
                                                    <option value="1"> 1x </option>
                                                    <option value="2"> 2x </option>
                                                    <option value="3"> 3x </option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->

                                    </div>
                                    <!-- Fim :: 3ª Linha - Card 2º Passo -->

                                    <!-- Inicio :: 4ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">

                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->    
                                        <div class="col-lg">
                                            <div class="input-group">                   
                                                <span class="input-group-text">Buscar:</span>
                                                <input class="campo-filtro form-control" type="search" placeholder="Pesquise uma pessoa" wire:model.live="query_filtro_pessoa" aria-label="Search">           
                                            </div> 
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->   

                                    </div>
                                    <!-- Fim :: 4ª Linha - Card 2º Passo -->

                                    <!-- Inicio :: 3ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">
                             
                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->    
                                        <div class="col-lg">
                                            <h6 class="text-center"> Código da pessoa selecionada:  {{ $this->filtro_pessoa_organizar }} </h6>                               
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->   

                                    </div>
                                    <!-- Fim :: 3ª Linha - Card 2º Passo -->

                                    <!-- Inicio :: 5ª Linha - Card 2º Passo -->
                                    <div class="row mb-3">

                                        <!-- Inicio :: 1ª Coluna - Card 2º Passo -->   
                                        <div class="col-lg">
                                            <table class="table-pessoa">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="width: 5%;"></th>                            
                                                        <th scope="col" style="width: 20%;">Foto</th>
                                                        <th scope="col" style="width: 10%;">ID</th>
                                                        <th scope="col" style="width: 65%;">Nome</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($listaPessoas as $pessoa)
                                                        <tr class="list-item {{ $pessoa->id == $filtro_pessoa_organizar ? 'selected' : '' }}"
                                                            wire:key="{{ $pessoa->id }}" 
                                                            wire:click="selecionarPessoa({{ $pessoa->id }})">

                                                            <td style="width: 5%;">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="radio" 
                                                                    name="rgPessoa" 
                                                                    id="rgPessoa_{{ $pessoa->id }}" 
                                                                    wire:model="filtro_pessoa_organizar" 
                                                                    value="{{ $pessoa->id }}"
                                                                    @if($pessoa->id == $filtro_pessoa_organizar) 
                                                                        checked 
                                                                    @endif
                                                                > 
                                                            </td>

                                                            <td class="p-2" style="width: 20%;">
                                                                @if ($pessoa->rostos->isNotEmpty())
                                                                    <img class="imagem-pessoa" src="{{ asset('storage/' . $pessoa->rostos[0]->url_rosto) }}" alt="{{ $pessoa->nome }}" width="120" height="120">
                                                                @else
                                                                    <p>Sem foto disponível</p>
                                                                @endif
                                                            </td>

                                                            <td class="p-2" style="width: 10%;">
                                                                <label class="form-check-label" for="rgPessoa_{{ $pessoa->id }}">{{ $pessoa->id }}</label>
                                                            </td>

                                                            <td class="p-2" style="width: 65%;">
                                                                <label class="form-check-label" for="rgPessoa_{{ $pessoa->id }}">{{ $pessoa->nome }}</label>
                                                            </td>

                                                        </tr>
                                                    @endforeach 
                                                </tbody>
                                            </table>
                                                                              

                                            <!-- Script JavaScript para registrar os caminhos das imagens no console -->
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const images = document.querySelectorAll('.imagem-pessoa');
                                                    images.forEach(function (img) {
                                                        console.log('Caminho da imagem: ' + img.src);
                                                    });
                                                });
                                            </script>

                                            {{ $listaPessoas->links() }}
                                        </div>
                                        <!-- Fim :: 1ª Coluna - Card 2º Passo -->   

                                    </div>
                                    <!-- Fim :: 5ª Linha - Card 2º Passo -->

                                </div>
                                <!-- Fim :: Conteudo - Card 2º Passo -->

                            </div>
                            <!-- Fim :: Card 2º Passo -->               

                        </div>   
                        <!-- Fim :: 1ª Coluna - Card Principal -->

                    </div>
                    <!-- Fim :: 2ª Linha - Card Principal -->

                    <!-- Inicio :: 3ª Linha - Card Principal -->
                    <div class="row mb-3">

                        <!-- Inicio :: 1ª Coluna - Card Principal -->
                        <div class="col-lg">  
                            <form wire:submit.prevent="organizar" wire:confirm="Deseja realmente organizar repositorio?">
                                <div class="d-grid gap-2 col-4 mx-auto mt-3">                  
                                    <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Organizar </button>
                                </div>
                            </form>
                        </div>
                        <!-- Fim :: 1ª Coluna - Card Principal -->

                    </div>
                    <!-- Fim :: 3ª Linha - Card Principal -->

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