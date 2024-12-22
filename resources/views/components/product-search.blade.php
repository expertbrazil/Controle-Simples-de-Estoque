@props(['id' => 'product-search'])

<div class="card mb-3">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Buscar Produto</label>
            <input type="text" 
                   class="form-control" 
                   id="{{ $id }}-input" 
                   placeholder="Digite o nome, SKU ou código de barras do produto">
        </div>
        <div id="{{ $id }}-results" class="list-group" style="max-height: 300px; overflow-y: auto;">
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const searchInput = $('#{{ $id }}-input');
    const resultsContainer = $('#{{ $id }}-results');
    let searchTimeout;

    searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();

        if (query.length < 2) {
            resultsContainer.empty();
            return;
        }

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("products.search") }}',
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    resultsContainer.empty();
                    
                    if (response.products.length === 0) {
                        resultsContainer.append(`
                            <div class="list-group-item text-muted">
                                Nenhum produto encontrado
                            </div>
                        `);
                        return;
                    }

                    response.products.forEach(function(product) {
                        resultsContainer.append(`
                            <button type="button" 
                                    class="list-group-item list-group-item-action product-item"
                                    data-id="${product.id}"
                                    data-name="${product.name}"
                                    data-sku="${product.sku || ''}"
                                    data-barcode="${product.barcode || ''}"
                                    data-stock="${product.stock_quantity}"
                                    data-price="${product.consumer_price}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">${product.name}</h6>
                                        <small class="text-muted">
                                            SKU: ${product.sku || 'N/A'} | 
                                            Código: ${product.barcode || 'N/A'}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div>R$ ${parseFloat(product.consumer_price).toFixed(2).replace('.', ',')}</div>
                                        <small class="text-muted">
                                            Estoque: ${product.stock_quantity}
                                        </small>
                                    </div>
                                </div>
                            </button>
                        `);
                    });
                },
                error: function(xhr) {
                    resultsContainer.empty().append(`
                        <div class="list-group-item text-danger">
                            Erro ao buscar produtos
                        </div>
                    `);
                }
            });
        }, 300);
    });

    // Evento para quando um produto é selecionado
    resultsContainer.on('click', '.product-item', function() {
        const product = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            sku: $(this).data('sku'),
            barcode: $(this).data('barcode'),
            stock: $(this).data('stock'),
            price: $(this).data('price')
        };

        // Emite um evento com os dados do produto
        $(document).trigger('product:selected', [product]);
        
        // Limpa a busca
        searchInput.val('');
        resultsContainer.empty();
    });
});
</script>
@endpush
