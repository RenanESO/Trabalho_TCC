<div>

    <!-- Início :: Principal -->
    <main class="container-fluid">

        <div id="menu">
            <h1 class="text-center mb-4">Escolha uma opção</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col-lg">
                    <div class="card m-2">
                        <img src="{{ asset('imagens/iconeOrganizar.png') }}" class="card-img-top" alt="card-Organizar">
                        <div class="card-body">
                            <h5 class="card-title">Organizar conjunto de fotos</h5>
                            <p class="card-text">
                                Organize suas fotos localizadas no Google Drive ou OneDrive pelos 
                                filtros de data e pessoas. Ajudando na organização de 
                                sua galeria.
                            </p>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <a href="{{ asset('organizar') }}" class="btn btn-primary">Selecionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="card m-2">
                        <img src="{{ asset('imagens/iconeDuplicidade.png') }}" class="card-img-top" alt="card-Duplicidade">
                        <div class="card-body">
                            <h5 class="card-title">Encontrar duplicidade de fotos</h5>
                            <p class="card-text">
                                Encontre possíveis duplicidades de fotos localizadas em uma pasta 
                                específica no Google Drive ou OneDrive.
                            </p>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <a href="{{ asset('duplicidade') }}" class="btn btn-primary">Selecionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <div class="card m-2">
                        <img src="{{ asset('imagens/iconeTreinamento.png') }}" class="card-img-top" alt="card-Treinamento">
                        <div class="card-body">
                            <h5 class="card-title">Treinamento IA</h5>
                            <p class="card-text">
                                Realize o treinamento manual de rosto para encontrar uma pessoa em específico 
                                em sua galeria de fotos localizada no Google Drive ou OneDrive.
                            </p>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <a href="{{ asset('treinamento') }}" class="btn btn-primary">Selecionar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- Fim :: Principal -->

</div>
