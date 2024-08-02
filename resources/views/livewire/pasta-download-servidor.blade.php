<div>
    <div class="list-view mt-4">
        @foreach ($arquivos as $arquivo)
            <div class="file-item">
                @if ($arquivo['tipoMime'] == 'application/vnd.google-apps.folder')
                    <img src="{{ asset('imagens/imgPasta.png') }}" alt="Folder" class="icon">
                    <button class="btn btn-light" wire:click="alterarPasta('{{ $arquivo['id'] }}')">{{ $arquivo['nome'] }}</button>
                @else
                    <img src="{{ asset('imagens/imgImagem.png') }}" alt="File" class="icon">
                    <span>{{ $arquivo['nome'] }}</span>
                    <a href="{{ $arquivo['linkVisualizacao'] }}" target="_blank"> Visualizar </a>
                @endif
            </div>
        @endforeach
    </div>

    <button class="btn btn-primary mt-4" wire:click="voltar"> Voltar </button>
    <button class="btn btn-success mt-4" wire:click="baixarPasta"> Selecionar </button>
</div>
