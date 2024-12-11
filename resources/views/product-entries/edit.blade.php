@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Editar Entrada</h1>
        <div>
            <a href="{{ route('product-entries.show', $productEntry) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('product-entries.update', $productEntry) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="card-subtitle mb-2 text-muted">Informações do Produto</h5>
                                <p class="mb-1"><strong>Nome:</strong> {{ $productEntry->product->name }}</p>
                                <p class="mb-1"><strong>SKU:</strong> {{ $productEntry->product->sku }}</p>
                                <p class="mb-0"><strong>Categoria:</strong> {{ $productEntry->product->category->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-subtitle mb-2 text-muted">Informações da Entrada</h5>
                                <p class="mb-1">
                                    <strong>Data:</strong> 
                                    {{ $productEntry->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Registrado por:</strong>
                                    {{ $productEntry->user->name }}
                                </p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_price" class="form-label">Preço de Compra (por unidade)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" 
                                               class="form-control @error('purchase_price') is-invalid @enderror" 
                                               id="purchase_price" 
                                               name="purchase_price" 
                                               value="{{ old('purchase_price', $productEntry->purchase_price_formatted) }}"
                                               required>
                                    </div>
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantidade</label>
                                    <input type="number" 
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" 
                                           name="quantity" 
                                           value="{{ old('quantity', $productEntry->quantity) }}"
                                           min="1"
                                           required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total da Compra</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" 
                                    class="form-control"
                                    id="total"
                                    readonly
                                    value="{{ $productEntry->total_formatted }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $productEntry->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Você pode adicionar observações sobre esta entrada de produto.
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <strong>Atenção:</strong> Ao alterar a quantidade ou preço, o sistema irá:
                            <ul class="mb-0">
                                <li>Atualizar o estoque do produto automaticamente</li>
                                <li>Atualizar o preço da última compra se esta for a entrada mais recente</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('product-entries.show', $productEntry) }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializa a máscara monetária
        var moneyMask = IMask(document.getElementById('purchase_price'), {
            mask: Number,
            scale: 2,
            signed: false,
            thousandsSeparator: '.',
            padFractionalZeros: true,
            normalizeZeros: true,
            radix: ',',
            min: 0,
            max: 999999.99,
            format: function (value) {
                return value.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },
            parse: function (value) {
                return Number(value.replace(/\./g, '').replace(',', '.'));
            }
        });

        // Atualiza a máscara com o valor inicial
        var initialValue = '{{ old('purchase_price', $productEntry->purchase_price_formatted) }}';
        moneyMask.value = initialValue;

        // Função para atualizar o total
        function updateTotal() {
            var price = moneyMask.unmaskedValue;
            var quantity = $('#quantity').val();
            var total = price * quantity;
            
            // Formata o total
            $('#total').val(total.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        // Atualiza o total quando o preço ou quantidade mudar
        $('#purchase_price, #quantity').on('input', updateTotal);
        
        // Atualiza o total inicial
        updateTotal();
    });
</script>
@endpush

@endsection
