@extends('layouts.app')

@section('content')
<div class="pdv-container">
    <!-- Seção de Produtos -->
    <div class="products-section">
        <div class="search-bar">
            <input type="text" id="product-search" class="search-input" 
                   placeholder="Buscar produtos por nome, código ou código de barras (F3)">
            <div class="keyboard-shortcut">F3</div>
        </div>
        <div class="categories-bar">
            <div class="categories-scroll">
                <button class="category-btn active" data-category="all">Todos</button>
                <!-- Categorias serão inseridas aqui dinamicamente -->
            </div>
        </div>
        <div class="products-grid" id="products-grid">
            <!-- Produtos serão listados aqui -->
        </div>
    </div>

    <!-- Seção do Carrinho -->
    <div class="cart-section">
        <!-- Header do Carrinho com Cliente -->
        <div class="cart-header">
            <div class="customer-select">
                <div class="selected-customer" id="selected-customer">
                    <i class="bi bi-person-circle"></i>
                    <span>Selecionar Cliente (F2)</span>
                </div>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#customerSearchModal">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <!-- Items do Carrinho -->
        <div class="cart-items" id="cart-items">
            <!-- Items serão inseridos aqui -->
        </div>

        <!-- Resumo do Carrinho -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="cart-subtotal">R$ 0,00</span>
            </div>
            <div class="summary-row">
                <span>Desconto</span>
                <div class="discount-input-group">
                    <input type="number" id="discount-input" class="form-control" value="0" min="0" max="100">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="summary-row total-row">
                <span>Total</span>
                <span id="cart-total">R$ 0,00</span>
            </div>

            <!-- Botões de Ação -->
            <div class="cart-actions">
                <button class="btn btn-success w-100 mb-2" id="finish-sale">
                    <i class="bi bi-check-circle"></i> Finalizar Venda (F4)
                </button>
                <div class="btn-group w-100">
                    <button class="btn btn-secondary" id="hold-sale" title="Segurar Venda (F6)">
                        <i class="bi bi-clock-history"></i>
                    </button>
                    <button class="btn btn-secondary" id="view-sales" title="Ver Vendas (F7)">
                        <i class="bi bi-list"></i>
                    </button>
                    <button class="btn btn-danger" id="cancel-sale" title="Cancelar Venda (F8)">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Busca de Cliente -->
<div class="modal fade" id="customerSearchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" id="customer-search" class="form-control" 
                           placeholder="Digite nome, CPF ou telefone...">
                    <button class="btn btn-primary" id="search-customer-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div id="customer-results" class="list-group">
                    <!-- Resultados da busca aparecerão aqui -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                        data-bs-target="#newCustomerModal">Novo Cliente</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Finalização -->
<div class="modal fade" id="finishSaleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Finalizar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="payment-methods mb-3">
                    <h6>Forma de Pagamento</h6>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary active" data-payment="money">
                            <i class="bi bi-cash"></i> Dinheiro
                        </button>
                        <button class="btn btn-outline-primary" data-payment="credit">
                            <i class="bi bi-credit-card"></i> Cartão de Crédito
                        </button>
                        <button class="btn btn-outline-primary" data-payment="debit">
                            <i class="bi bi-credit-card"></i> Cartão de Débito
                        </button>
                        <button class="btn btn-outline-primary" data-payment="pix">
                            <i class="bi bi-qr-code"></i> PIX
                        </button>
                    </div>
                </div>

                <div class="payment-details">
                    <!-- Campos específicos para cada forma de pagamento -->
                </div>

                <div class="sale-summary mt-3">
                    <h6>Resumo da Venda</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-end" id="modal-subtotal">R$ 0,00</td>
                            </tr>
                            <tr>
                                <td>Desconto:</td>
                                <td class="text-end" id="modal-discount">R$ 0,00</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong id="modal-total">R$ 0,00</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirm-sale">
                    <i class="bi bi-check-circle"></i> Confirmar Venda
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pdv.css') }}">
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        let cart = [];
        let allProducts = [];

        // Função para formatar valor em reais
        function formatMoney(value) {
            return parseFloat(value).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2
            });
        }

        // Função para formatar número
        function formatNumber(value) {
            return parseFloat(value).toFixed(2);
        }

        // Carregar produtos inicialmente
        loadProducts();

        function loadProducts() {
            console.log('Iniciando carregamento de produtos...');
            $.get('/products/for-sale')
                .done(function(response) {
                    console.log('Produtos carregados:', response);
                    allProducts = response;
                    displayProducts(allProducts);
                })
                .fail(function(error) {
                    console.error('Erro ao carregar produtos:', error);
                    alert('Erro ao carregar produtos. Por favor, recarregue a página.');
                });
        }

        // Busca de produtos
        $('#product-search').on('input', function() {
            let query = $(this).val().toLowerCase();
            
            if (query.length === 0) {
                displayProducts(allProducts);
                return;
            }

            let filteredProducts = allProducts.filter(product => 
                product.name.toLowerCase().includes(query) || 
                (product.sku && product.sku.toLowerCase().includes(query))
            );
            
            displayProducts(filteredProducts);
        });

        // Exibir produtos
        function displayProducts(products) {
            let html = '';
            products.forEach(product => {
                const imageUrl = product.image_url || '/images/produtos/no-image.jpg';
                    
                html += `
                    <div class="col">
                        <div class="card h-100 product-card">
                            <img src="${imageUrl}" class="card-img-top product-img" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">
                                    SKU: ${product.sku || 'N/A'}<br>
                                    Estoque: ${product.stock}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">R$ ${product.price}</span>
                                    <button class="btn btn-primary add-to-cart" 
                                            data-id="${product.id}"
                                            data-name="${product.name}"
                                            data-price="${product.price}"
                                            data-stock="${product.stock}">
                                        <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            $('#products-grid').html(html);

            // Adicionar evento aos botões
            $('.add-to-cart').click(function() {
                const button = $(this);
                const product = {
                    id: button.data('id'),
                    name: button.data('name'),
                    price: parseFloat(button.data('price')),
                    stock: parseInt(button.data('stock')),
                    quantity: 1
                };
                addToCart(product);
            });
        }

        function addToCart(product) {
            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++;
                    toastr.success('Quantidade atualizada no carrinho');
                } else {
                    toastr.warning('Quantidade máxima atingida');
                    return;
                }
            } else {
                if (product.stock > 0) {
                    cart.push(product);
                    toastr.success('Produto adicionado ao carrinho');
                } else {
                    toastr.error('Produto sem estoque');
                    return;
                }
            }
            
            updateCart();
        }

        function updateCart() {
            let html = '';
            let subtotal = 0;
            
            cart.forEach((item, index) => {
                const total = item.price * item.quantity;
                subtotal += total;
                
                html += `
                    <div class="cart-item mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${item.name}</h6>
                                <small class="text-muted">
                                    ${formatMoney(item.price)} x ${item.quantity} = ${formatMoney(total)}
                                </small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary decrease-quantity" data-index="${index}">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <button class="btn btn-outline-primary increase-quantity" data-index="${index}">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <button class="btn btn-outline-danger remove-from-cart" data-index="${index}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
            });

            $('#cart-items').html(html);
            $('#cart-subtotal').text(formatMoney(subtotal));
            
            // Atualizar botões de quantidade
            $('.decrease-quantity').click(function() {
                const index = $(this).data('index');
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                    updateCart();
                    toastr.success('Quantidade atualizada');
                }
            });
            
            $('.increase-quantity').click(function() {
                const index = $(this).data('index');
                if (cart[index].quantity < cart[index].stock) {
                    cart[index].quantity++;
                    updateCart();
                    toastr.success('Quantidade atualizada');
                } else {
                    toastr.warning('Quantidade máxima atingida');
                }
            });
            
            $('.remove-from-cart').click(function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                updateCart();
                toastr.success('Produto removido do carrinho');
            });

            // CEP autocomplete
            $('#cep').on('blur', function() {
                const cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.get(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                        if (!data.erro) {
                            $('#endereco').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                        }
                    });
                }
            });

            // Handle new customer form submission
            $('#salvarNovoCliente').click(function() {
                const formData = new FormData($('#novoClienteForm')[0]);
                
                $.ajax({
                    url: '/customers',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Update the cliente selecionado section
                            $('#clienteId').val(response.customer.id);
                            $('#clienteNome').text(response.customer.nome);
                            $('#clienteInfo').text(`CPF: ${response.customer.cpf || 'N/A'} - Tel: ${response.customer.telefone || 'N/A'}`);
                            $('#clienteSelecionado').show();
                            
                            // Close the modal
                            $('#novoClienteModal').modal('hide');
                            
                            // Clear the form
                            $('#novoClienteForm')[0].reset();
                            
                            // Show success message
                            alert('Cliente cadastrado com sucesso!');
                        } else {
                            alert('Erro ao cadastrar cliente. Por favor, tente novamente.');
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao cadastrar cliente. Por favor, tente novamente.');
                    }
                });
            });

            // Format CPF input
            $('#cpf').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                    $(this).val(value);
                }
            });

            // Format telefone input
            $('#telefone').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length <= 11) {
                    if (value.length === 11) {
                        value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    } else {
                        value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    }
                    $(this).val(value);
                }
            });

            // Format CEP input
            $('#cep').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length <= 8) {
                    value = value.replace(/(\d{5})(\d{3})/, '$1-$2');
                    $(this).val(value);
                }
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .product-card {
        transition: transform 0.2s;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .product-img {
        height: 150px;
        object-fit: cover;
        object-position: center;
    }

    .cart-container {
        max-height: calc(100vh - 400px);
        overflow-y: auto;
    }

    .product-search-container {
        position: sticky;
        top: 0;
        background: white;
        padding: 1rem;
        z-index: 1000;
        border-bottom: 1px solid #dee2e6;
    }

    #products-grid {
        padding: 1rem;
    }

    .card-title {
        font-size: 0.9rem;
        line-height: 1.2;
        height: 2.4em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .cart-item {
        padding: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .cart-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
@endsection
