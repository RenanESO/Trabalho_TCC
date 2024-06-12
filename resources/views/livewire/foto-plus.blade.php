<div>
    
    <!-- Inicio :: Main -->
    <main>

        <div class="container-fluid">

            <div id="carousel-fotoplus" class="carousel slide carousel-fade" data-bs-ride="carousel"> 

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carousel-fotoplus" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carousel-fotoplus" data-bs-slide-to="1" aria-label="Slide 2"></button>
                </div>

                <div class="carousel-inner">

                    <div class="carousel-item active" data-bs-interval="100000">
                        <div id="main-slider" class="container-fluid bg-white text-black">
                            <div>
                                <div class="row gx-5">
                                    <div class="col-lg">
                                        <div class="p-3">
                                            <h1>
                                                Repositório <br> Otimizado
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-5">
                                    <div class="col-lg-6">
                                        <div class="p-3">
                                            <h3>
                                                Resolva seus problemas de organização com os repositórios de fotos, 
                                                utilizando a inteligência do FotoPlus. Organize fotos por datas, 
                                                locais, pessoas e outros filtros.
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="p-3">
                                            <!-- Coluna Vazia -->   
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-5">
                                    <div class="col-lg">
                                        <div class="p-3">
                                            <a href="{{ asset('login') }}" class="btn btn-success"> Confira Agora </a>
                                        </div>
                                    </div>
                                </div>
                                <br> <br>
                            </div>
                        </div>   
                    </div>

                    <div class="carousel-item" data-bs-interval="100000">
                        <div id="servico" class="container-fluid bg-white text-black">
                            <h1 class="text-center mb-4"> Serviços </h1> 
                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                <div class="col">
                                    <div class="card m-2">
                                        <img src="{{ asset('imagens/imgIA.png') }}" class="card-img-top" alt="card-RedeNeural">
                                        <div class="card-body">
                                            <h5 class="card-title"> Inteligência </h5>
                                            <p class="card-text">
                                                Utilizamos os principais metódos para reconhecer padrões em dados, 
                                                especialmente em imagens, como Histogram of Oriented Gradients - HOG 
                                                e Rede Neural Convolucional - CNN.
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-muted"> FotoPlus é Eficiente </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card m-2">
                                        <img src="{{ asset('imagens/imgSeguranca.png') }}" class="card-img-top" alt="card-Seguranca">
                                        <div class="card-body">
                                            <h5 class="card-title"> Segurança </h5>
                                            <p class="card-text">
                                                Sinta-se seguro utilizando nossos serviços, oferecendo 
                                                um alto nível de segurança para sua imagens e dados pessoais.                        
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-muted"> FotoPlus é Confiabilidade </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card m-2">
                                        <img src="{{ asset('imagens/imgOrganizacao.png') }}" class="card-img-top" alt="card-Organizacao">
                                        <div class="card-body">
                                            <h5 class="card-title"> Organização </h5>
                                            <p class="card-text">
                                                Tenha uma experiência melhorada em gerenciar suas imagens 
                                                no Google Drive. Filtre fotos pela data e pessoa 
                                                específica, além disso pode veificar fotos duplicadas.
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            <small class="text-muted"> FotoPlus é Inovador </small>
                                        </div>
                                    </div>
                                </div>
                            </div>          
                        </div>
                    </div>

                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-fotoplus" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"> Anterior </span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-fotoplus" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"> Próximo </span>
                </button>

            </div>

        </div>

        <div class="container-fluid" >
            <div id="sobre"> 
                <h1 class="text-center mb-4"> Sobre Nós </h1> 
                <div  class="row align-items-center">
                    <div class="col">
                        <div class="card">
                            <a href="https://www.formiga.ifmg.edu.br">
                                <img id="image-instituicao" src="{{ asset('imagens/IFMG.png') }}" alt="imagem-campus" class="img-fluid">
                            </a>    
                            <div class="card-body">
                                <p class="card-text fs-4 text-md">
                                    A FotoPlus foi concebida como parte do Trabalho de Conclusão de Curso (TCC) 
                                    do aluno Renan Evilásio Silva de Oliveira, estudante do curso de Ciência da 
                                    Computação do Instituto Federal de Minas Gerais, campus Formiga. O projeto 
                                    visa otimizar e facilitar a organização e a busca de fotografias armazenadas. 
                                    A iniciativa surge da necessidade de uma solução eficiente para a gestão de 
                                    imagens, proporcionando aos usuários uma ferramenta que simplifique o processo 
                                    de categorização e localização de fotos em grandes volumes de armazenamento. 
                                    Este projeto reflete o compromisso acadêmico e a inovação tecnológica no 
                                    desenvolvimento de soluções práticas para desafios contemporâneos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row align-items-center">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-8">
                                <div class="card-body">
                                    <p id="descricao-aluno" class="fs-5 text-md fst-italic">
                                        "Olá, pessoal! Meu nome é Renan Evilásio, entusiasta por tecnologia e possuo mais 
                                        de 5 anos de experiência em Delphi. Também tenho conhecimentos intermediários em 
                                        Python e iniciante em Laravel. Acredito que a organização do código é fundamental 
                                        para a manutenção e compreensão do mesmo. Além disso, estou sempre disposto a 
                                        aprender novas tecnologias para acompanhar a constante evolução da área."
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img id="image-aluno" src="{{ asset('imagens\Renan.2.1.jpg') }}" alt="Sua Imagem" class="img-fluid rounded-circle">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- Fim :: Main -->

</div>
