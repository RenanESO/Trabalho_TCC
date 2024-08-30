<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('imagens/iconeABA.png') }}">

        <!-- Fontes -->
        <link 
            href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" 
            rel="stylesheet"
        > 

        <!-- CSS Aplicacao -->
        <link 
            rel="stylesheet" 
            href="{{ asset('css/styles.css') }}"
        >   

        <!-- CSS Bootstrap -->
        <link 
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" 
            rel="stylesheet" 
            integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" 
            crossorigin="anonymous"
        >   

        <link 
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
            rel="stylesheet"
        >

        <title>{{ $title ?? 'FotoPlus' }}</title> 

        <!-- Styles -->
        @livewireStyles
    </head>

    @php
        $loading = false; // Definir a variável $loading como true para mostrar o overlay
    @endphp

    <body>      
        <!-- Inicio :: Header -->
        <header id="inicio">
            <!-- Inicio :: Barra de Navegacao no Cabecalho -->
            <div id="nav-container" class="container-fluid">
                <nav class="navbar navbar-expand-md">
                    <!-- Icone Logo -->
                    <a class="navbar-brand" style="display: flex; align-items: center;" href="{{ route('fotoplus') }}">
                        <div>
                            <img src="{{ asset('imagens/iconeNav.png') }}" width="35" height="35" alt="Logo"> 
                        </div>
                        <div class="mx-2">
                            <!-- Nome da aplicação -->
                            <span> {{ __('FotoPlus') }} </span>
                        </div> 
                    </a>

                    <!-- Nome Usuario -->
                    @auth
                        <div id="bem-vindo">
                            <span> Bem vindo {{ Auth::user()->name }} #{{ Auth::user()->id }} </span>     
                        </div>     
                    @endauth

                    <!-- Botão para NavBar (Resolução Baixa) -->
                    <button class="navbar-toggler mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-links" aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> 

                    <!-- Itens do NavBar -->
                    <div id="navbar-links" class="collapse navbar-collapse d-lg-flex align-items-center justify-content-end">
                        <ul class="navbar-nav">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="#sobre"> {{ __('Sobre Nós') }} </a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}"> {{ __('Registrar') }} </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a id="nav-link-entrar" class="nav-link" href="{{ route('login') }}"> {{ __('Entrar') }} </a>
                                </li>        
                            @endguest

                            @auth
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}"> {{ __('Painel') }} </a>
                                </li>
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('profile.show') }}"> {{ __('Configuração') }} </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('ajuda') }}"> {{ __('Ajuda') }} </a>
                                </li>
                                <li class="nav-item">                      
                                    <form action="{{ route('logout') }}" method="POST"> 
                                        @csrf 
                                        <a id="nav-link-sair" href="{{ route('sair') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();"> 
                                            {{ __('Sair') }}  
                                        </a>   
                                    </form>
                                </li>                                
                            @endauth
                        </ul>
                    </div>    
                </nav>
            </div>
            <!-- Fim :: Barra de Navegacao no Cabecalho -->
        </header>
        <!-- Fim :: Header -->

        {{ $slot }}   

        <!-- Inicio :: Footer -->
        <footer class="footer mt-auto">
            <ul class="social-icons">
                <li><p> FotoPlus &copy;Copyright 2023-2024 </p></div>
                <li><p> Visite as Redes Sociais </p></li>
                <li><a href="https://www.linkedin.com/in/renan-evilásio-43b357247" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                <li><a href="https://www.instagram.com/renan_eso/" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </ul>
        </footer>       
        <!-- Fim :: Footer --> 

        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

        <!-- JS Bootstrap Bundle with Popper -->
        <script 
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" 
            crossorigin="anonymous">
        </script>
        <script 
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" 
            crossorigin="anonymous">
        </script>

        <!-- <script src="{ asset('js/scripts.js') }"></script> -->

        <!-- Icones IonIcons -->
        <script  
            type="module"  src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"> 
        </script>
        <script  
            nomodule src="https://unpkg .com/ionicons@7.1.0/dist/ionicons/ionicons.js"> 
        </script>

        @livewireScripts
    </body>
</html>

