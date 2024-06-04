<div>
    
    <!-- Inicio :: Main -->
    <main>
        <div id="main-slider" class="container-fluid mx-auto py-4 bg-white text-black" >
            <div class="container m-auto py-5">
                <div class='container-sm' style="margin-right:20rem;">
                    <div class="row gx-5">
                        <div class="col-lg">
                            <div class="p-3">
                                <h1>
                                    Repositorio <br> Otimizado
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-5">
                        <div class="col-lg">
                            <div class="p-3">
                                <h3>
                                    Resolva seus problemas de organização com os repositorios de fotos, 
                                    utilizando a inteligência do FotoPlus. Organize fotos por datas, 
                                    locais, pessoas e outros filtros.
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg">
                            <div class="p-3">
                                <!-- Coluna Vazia -->   
                            </div>
                        </div>
                    </div>
                    <div class="row gx-5">
                        <div class="col-lg">
                            <div class="p-3">
                                <a href="{{ asset('login') }}" class="btn btn-success">Confira Agora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="servico" class="container-fluid mx-auto py-4">
            <div class="container m-auto">
                <h1 class="text-center">Serviços</h1>
                <div class="row row-cols-1 row-cols-md-3 g-4 py-4">
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset('imagens/imgIA.png') }}" class="card-img-top" alt="card-RedeNeural">
                            <div class="card-body">
                                <h5 class="card-title">Redes Neurais CNN</h5>
                                <p class="card-text">
                                    Uma Rede Neural Convolucional (Convolutional Neural 
                                    Network - CNN) é um tipo de algoritmo de 
                                    aprendizado de máquina que pode ser usado para reconhecer 
                                    padrões em dados, especialmente em imagens.
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">FotoPlus é Eficiente</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset('imagens/imgSeguranca.png') }}" class="card-img-top" alt="card-Seguranca">
                            <div class="card-body">
                                <h5 class="card-title">Segurança</h5>
                                <p class="card-text">
                                    Sinta-se seguro utilizando nossos serviços, oferecendo 
                                    um alto nivel de segurança para os dados.
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">FotoPlus é Confiabilidade</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ asset('imagens/imgOrganizacao.png') }}" class="card-img-top" alt="card-Organizacao">
                            <div class="card-body">
                                <h5 class="card-title">Organização</h5>
                                <p class="card-text">
                                    Tenha uma esperiencia melhorada em gerenciar suas fotos 
                                    nos principais armazenamentos como: Google Drive e OneDrive. 
                                    Filtre fotos pela data e local, podendo eliminar fotos 
                                    duplicadas e buscar fotos de pessoas espeficas.
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">FotoPlus é Inovador</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div id="sobre" class="container-fluid mx-auto py-4" >
            <div class="container m-auto">
                <h1 class="text-center">Sobre Nós</h1>
                <div id="img-campus" class="row align-items-center">
                    <div class="col">
                        <div class="card border-0 h-100 py-4">
                            <a href="https://www.formiga.ifmg.edu.br">
                                <img src="{{ asset('imagens/IFMG.png') }}" alt="img-campus">
                            </a>                      
                            <div class="card-body">
                                <h2 class="card-text py-3">
                                    FotoPlus teve iniciativa pelo projeto de TCC do aluno 
                                    Renan Evilásio Silva de Oliveira, bacharelando do curso Ciência da Computação 
                                    do Instituto Federal de Minas Gerais campus Formiga. Com o 
                                    objetivo de otimizar e facilitar a organização e busca de 
                                    fotografias contidas em armazenamentos.
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- Fim :: Main -->

</div>
