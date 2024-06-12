<div>
    <div id="menu" class="container-fluid" >
        <h1 class="text-center mb-4"> Escolha uma opção </h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card m-2">
                    <img src="{{ asset('imagens/iconeOrganizar.png') }}" class="card-img-top" alt="card-RedeNeural">
                    <div class="card-body">
                        <h5 class="card-title"> Organizar conjunto de fotos </h5>
                        <p class="card-text">
                            Organize suas fotos localizados no Google Drive ou OneDrive, pelos 
                            filtros de data e pessoas. Ajudando na organização de 
                            sua galeria.
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ asset('organizar') }}" class="btn btn-primary"> Selecionar </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card m-2">
                    <img src="{{ asset('imagens/iconeDuplicidade.png') }}" class="card-img-top" alt="card-Seguranca">
                    <div class="card-body">
                        <h5 class="card-title"> Encontrar duplicidade de fotos </h5>
                        <p class="card-text">
                            Encontre possiveis duplicidade de fotos localizado em especifica pasta 
                            no Google Drive ou OneDrive.
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ asset('duplicidade') }}" class="btn btn-primary"> Selecionar </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card m-2">
                    <img src="{{ asset('imagens/iconeTreinamento.png') }}" class="card-img-top" alt="card-Organizacao">
                    <div class="card-body">
                        <h5 class="card-title"> Treinamento IA </h5>
                        <p class="card-text">
                            Realize o treinamento manual de rosto para encontrar uma pessoaem especifico 
                            em sua galeria de fotos localizados no Google Drive ou OneDrive
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ asset('treinamento') }}" class="btn btn-primary"> Selecionar </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
