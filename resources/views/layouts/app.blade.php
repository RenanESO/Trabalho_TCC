<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts 
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

        <!-- Fontes -->
        <link 
            href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" 
            rel="stylesheet"
        > 

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    
    <!-- <body class="font-sans antialiased">  class="bg-gray-100"-->
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
                        <div style="margin-left: 10px;">
                            <!-- Nome da aplicação -->
                            <span> {{ __('FotoPlus') }} </span>
                        </div>              
                    </a>
                    <!-- Botão para NavBar (Resolução Baixa) -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-links" aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> 
                    <!-- Itens do NavBar -->
                    <div id="navbar-links" class="collapse navbar-collapse d-lg-flex align-items-center justify-content-end">
                        <ul class="navbar-nav">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="#servico"> {{ __('Serviços') }} </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#sobre"> {{ __('Sobre Nós') }} </a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">  {{ __('Registrar') }} </a>
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
                                <li class="nav-item"> <!-- profile.show OU perfil  -->
                                    <a class="nav-link" href="{{ route('profile.show') }}"> {{__('Configuração')}} </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('ajuda') }}"> {{ __('Ajuda') }} </a>
                                </li>
                                <li class="nav-item">                      
                                    <form action="{{ route('logout') }}" method="POST"> 
                                        @csrf 
                                        <a id="nav-link-sair" href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();"> 
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

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @stack('modals')

        @livewireScripts
    </body>
    
</html>
