@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
    .ui-autocomplete {
        z-index: 9999;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .product-row:not(:last-child) {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    .remove-product {
        cursor: pointer;
    }
    .customer-info {
        background-color: #e3f2fd;
        padding: 10px 15px;
        border-radius: 5px;
        margin-top: 10px;
    }
    .product-container {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .totals-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
    }
    .product-header {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        font-weight: bold;
    }
    .stock-available {
        background-color: #e7f3ff !important;
    }
    .stock-unavailable {
        background-color: #fce7f3 !important;
    }
    #search_results {
        position: relative;
        z-index: 1000;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-top: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    #search_results .table {
        margin-bottom: 0;
    }
    #search_results .table-responsive {
        max-height: 300px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h2><i class="bi bi-cart-plus"></i> PDV - Nova Venda</h2>

    <form id="saleForm" action="{{ route('pdv.store') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-body">
                <!-- Cliente -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_search">Cliente *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" id="customer_search" class="form-control" placeholder="Digite o nome do cliente">
                            <input type="hidden" id="customer_id" name="customer_id">
                        </div>
                        <div class="customer-info mt-2" style="display: none;">
                            <span class="badge bg-success">
                                Cliente selecionado: <span id="customer_name"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Busca de Produtos -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="product_search" class="form-control" placeholder="Digite o nome ou código do produto">
                        </div>
                    </div>
                </div>

                <!-- Produtos Selecionados -->
                <div id="products_container" class="row mb-3">
                </div>

                <!-- Totais -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Subtotal</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="subtotal" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Desconto</label>
                            <div class="input-group">
                                <input type="radio" class="btn-check" name="discount_type" id="discount_type_percentage" value="percentage" checked>
                                <label class="btn btn-outline-secondary" for="discount_type_percentage">%</label>
                                <input type="number" class="form-control" id="discount_value" name="discount_value" placeholder="%" min="0" max="100" step="0.1">
                                <span class="input-group-text">ou</span>
                                <input type="radio" class="btn-check" name="discount_type" id="discount_type_fixed" value="fixed">
                                <label class="btn btn-outline-secondary" for="discount_type_fixed">R$</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Total</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="total_amount" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Método de Pagamento</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="money">Dinheiro</option>
                                <option value="credit_card">Cartão de Crédito</option>
                                <option value="debit_card">Cartão de Débito</option>
                                <option value="pix">PIX</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Observações</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Finalizar Venda
                        </button>
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Confirmar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="sale-summary">
                    <p><strong>Cliente:</strong> <span id="modal-customer"></span></p>
                    <p><strong>Subtotal:</strong> R$ <span id="modal-subtotal"></span></p>
                    <p><strong>Desconto:</strong> R$ <span id="modal-discount"></span></p>
                    <p><strong>Total:</strong> R$ <span id="modal-total"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirm-sale">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(document).ready(function() {
    // Busca de clientes
    $("#customer_search").autocomplete({
        source: "{{ route('pdv.search-customers') }}",
        minLength: 2,
        select: function(event, ui) {
            $("#customer_id").val(ui.item.id);
            $("#customer_name").text(ui.item.label);
            $(".customer-info").show();
            return false;
        }
    });

    // Busca de produtos
    $("#product_search").autocomplete({
        source: "{{ route('pdv.search-products') }}",
        minLength: 2,
        select: function(event, ui) {
            addProduct(ui.item);
            $(this).val('');
            return false;
        }
    });

    // Adicionar produto
    function addProduct(product) {
        const rowCount = $('.selected-product-row').length;
        const rowHtml = `
            <div class="row selected-product-row align-items-center mb-3 ${product.stock > 0 ? 'stock-available' : 'stock-unavailable'}">
                <input type="hidden" name="products[${rowCount}][product_id]" value="${product.id}">
                <div class="col-md-4">
                    <strong>${product.label}</strong>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="number" 
                               name="products[${rowCount}][quantity]" 
                               class="form-control product-quantity" 
                               value="1" 
                               min="1" 
                               max="${product.stock}"
                               required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" 
                               name="products[${rowCount}][price]" 
                               class="form-control product-price" 
                               value="${product.price}" 
                               step="0.01" 
                               required>
                    </div>
                </div>
                <div class="col-md-2">
                    <strong class="product-total">R$ ${product.price}</strong>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        $("#products_container").append(rowHtml);
        updateTotals();
    }

    // Remover produto
    $(document).on('click', '.remove-product', function() {
        $(this).closest('.selected-product-row').remove();
        updateTotals();
    });

    // Atualizar quantidade ou preço
    $(document).on('change', '.product-quantity, .product-price', function() {
        const row = $(this).closest('.selected-product-row');
        const quantity = parseFloat(row.find('.product-quantity').val()) || 0;
        const price = parseFloat(row.find('.product-price').val()) || 0;
        const total = quantity * price;
        
        row.find('.product-total').text(`R$ ${total.toFixed(2)}`);
        updateTotals();
    });

    // Atualizar totais
    function updateTotals() {
        let subtotal = 0;
        $('.selected-product-row').each(function() {
            const quantity = parseFloat($(this).find('.product-quantity').val()) || 0;
            const price = parseFloat($(this).find('.product-price').val()) || 0;
            subtotal += quantity * price;
        });

        $('#subtotal').val(subtotal.toFixed(2));
        
        // Calcular desconto
        const discountType = $('input[name="discount_type"]:checked').val();
        const discountValue = parseFloat($('#discount_value').val()) || 0;
        let discount = 0;
        
        if (discountType === 'percentage') {
            discount = (subtotal * discountValue) / 100;
            if (discountValue > 100) {
                $('#discount_value').val(100);
                discount = subtotal;
            }
        } else {
            discount = discountValue;
            if (discount > subtotal) {
                $('#discount_value').val(subtotal.toFixed(2));
                discount = subtotal;
            }
        }
        
        const total = subtotal - discount;
        $('#total_amount').val(total.toFixed(2));
    }

    // Atualizar desconto ao mudar tipo ou valor
    $('input[name="discount_type"], #discount_value').on('change', updateTotals);

    // Submeter venda
    $('#saleForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#customer_id').val()) {
            alert('Por favor, selecione um cliente.');
            return;
        }
        
        if ($('.selected-product-row').length === 0) {
            alert('Por favor, adicione pelo menos um produto.');
            return;
        }

        if (!$('select[name="payment_method"]').val()) {
            alert('Por favor, selecione o método de pagamento.');
            return;
        }
        
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        submitButton.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = "{{ route('sales.index') }}";
                } else {
                    alert(response.message);
                    submitButton.prop('disabled', false);
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Erro ao processar a venda.');
                submitButton.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection
