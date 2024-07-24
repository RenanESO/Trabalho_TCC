<div>

    <!-- Inicio :: Main -->
    <main class="container-fluid">

        <!-- Inicio :: Formulario -->
        <div class="formulario">

            <!-- Inicio :: Tela Cinza Carregamento -->
            <div class="overlay" wire:loading wire:target="treinarPessoa, cadastrarPessoa"> </div>
            <!-- Fim :: Tela Cinza Carregamento -->

            <!-- Inicio :: Carregamento -->
            <div class="alert alert-primary text-center shadow-sm p-3 mt-4 rounded"  wire:loading.grid wire:target="cadastrarPessoa, treinarPessoa">
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

            <!-- Inicio :: Card Principal -->
            <div class="card bg-body">

                <!-- Inicio :: Titulo Card Principal-->
                <div class="card-header">
                    <h3 class="text-center"> Treinamento imagem de uma pessoa </h3>
                </div>
                <!-- Fim :: Titulo Card Principal-->

                <!-- Inicio :: Conteudo Card -->
                <div class="card-body">

                    <!-- Inicio :: 1ª Linha -->
                    <div class="row">

                        <!-- Inicio :: 1ª Coluna -->
                        <div class="col-lg">

                            <!-- Inicio :: 1º Card -->
                            <div class="card">

                                <!-- Inicio :: Titulo Card -->
                                <div class="card-header">
                                    <h4 class="text"> 1º Passo: Carregar imagem </h4>
                                </div>
                                <!-- Fim :: Titulo Card -->

                                <!-- Inicio :: Conteudo Card -->
                                <div class="card-body">

                                    <!-- Inicio :: Buscar Imagem -->
                                    <form wire:submit.prevent="buscarImagem"> 
                                        <label class="custom-file-upload">
                                            
                                            <input type="file" wire:model="image_pessoa_treinamento" required>
                                            Selecionar Ficheiro
                                            
                                        </label>
                                        @error('image_pessoa_treinamento') 
                                            <span class="error"> {{ $message }} </span> 
                                        @enderror
                                        <br><br>
                                        @if ($image_pessoa_treinamento)
                                            <img id="imagem-treinamento" src="{{ $image_pessoa_treinamento->temporaryUrl() }}">
                                        @endif
                                    </form>
                                    <!-- Fim :: Buscar Imagem -->

                                </div>
                                <!-- Fim :: Conteudo Card -->

                            </div>
                            <!-- Fim :: 1º Card -->

                        </div>
                        <!-- Fim :: 1ª Coluna -->

                        <!-- Inicio :: 2ª Coluna -->
                        <div class="col-lg">

                            <!-- Inicio :: 2º Card -->
                            <div class="card">

                                <!-- Inicio :: Titulo Card -->
                                <div class="card-header">
                                    <h4 class="text">2º Passo: Cadastrar/Treinar pessoa</h4>
                                </div>
                                <!-- Fim :: Titulo Card -->

                                <!-- Inicio :: Conteudo Card -->
                                <div class="card-body">

                                    <!-- Inicio :: Cadastrar e Treinar -->
                                    <form class="col-lg" wire:submit.prevent="cadastrarPessoa" wire:confirm="Deseja realmente cadastrar essa Pessoa?">   
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Nome:</span>
                                            <input class="campo-filtro form-control" type="text" placeholder="Nova Pessoa" wire:model="nome_pessoa_cadastro" required>
                                        </div>      
                                        <div class="d-grid gap-2 col-4 mx-auto">                  
                                            <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Cadastrar & Treinar </button>
                                        </div>          
                                    </form>
                                    <!-- Fim :: Cadastrar e Treinar -->

                                    <br>

                                    <!-- Inicio :: Pesquisar Pessoa -->
                                    <div class="col-lg">
                                        <div class="input-group mb-3">                   
                                            <span class="input-group-text">Buscar:</span>
                                            <input class="campo-filtro form-control" type="search" placeholder="Pessoa" wire:model="query_pessoas_cadastro" aria-label="Search" wire:input.debounce.300ms="reiniciarPaginacao">           
                                        </div> 
                                    </div>

                                    <table class="table-pessoa">
                                        <tbody class="form-check">
                                            @foreach($listaPessoas as $pessoa)
                                            <tr class="list-group-item d-flex align-items-center">
                                                    <td class="p-2"> 
                                                        <input class="form-check-input" type="radio" name="rgPessoa" id="rgPessoa" wire:model="id_pessoa_treinamento" value={{ $pessoa->id }}> 
                                                    </td>
                                                    <td class="p-2"> 
                                                        @if ($pessoa->rostos->isNotEmpty())
                                                            <img class="imagem-pessoa" src="{{ asset('storage/' . $pessoa->rostos[0]->url_rosto) }}" alt={{ $pessoa->nome }} width="120" height="120">
                                                        @else
                                                            <p>Sem foto disponível</p>
                                                        @endif
                                                    </td>
                                                    <td class="p-2"> 
                                                        <label class="form-check-label" for="rgPessoa"> {{ $pessoa->nome }} </label>
                                                    </td>
                                            </tr>
                                            @endforeach 
                                        </tbody>
                                    </table>

                                    <!-- Script JavaScript para registrar os caminhos das imagens no console -->
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const images = document.querySelectorAll('.img-pessoa');
                                            images.forEach(function (img) {
                                                console.log('Caminho da imagem: ' + img.src);
                                            });
                                        });
                                    </script>

                                    {{ $listaPessoas->links() }}
                                    <!-- Fim :: Pesquisar Pessoa -->

                                    <!-- Inicio :: Treinar Pessoa -->
                                    <form class="col-lg" wire:submit.prevent="treinarPessoa" wire:confirm="Deseja realmente treinar essa Pessoa?">
                                        <div class="d-grid gap-2 col-4 mx-auto">                  
                                            <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Treinar Selecionado </button>
                                        </div>
                                    </form>
                                    <!-- Fim :: Treinar Pessoa -->

                                </div>
                                <!-- Fim :: Conteudo Card -->

                            </div>
                            <!-- Fim :: 2º Card -->

                        </div>
                        <!-- Fim :: 2ª Coluna -->

                    </div>
                    <!-- Fim :: 1ª Linha -->

                </div>
                <!-- Fim :: Conteudo Card Principal -->

            </div>
            <!-- Fim :: Card Principal -->

        </div>
        <!-- Fim :: Formulario -->

    </main>
    <!-- Fim :: Main -->

</div>

<script>
    function voltaInicio() {
        var element = document.getElementById("inicio");
        if (element) {
            element.scrollIntoView({ behavior: "smooth" });
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('personTable');
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header row
            rows[i].addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Remove selected class from all rows
                for (let j = 1; j < rows.length; j++) {
                    rows[j].classList.remove('selected');
                }
                
                // Add selected class to the clicked row
                this.classList.add('selected');
            });
        }
    });
</script> 
