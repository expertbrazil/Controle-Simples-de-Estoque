@extends('layouts.app')

@section('content')
<div class="content-pdv">
    <div class="header-pdv">
        <div class="row">
            <!-- Área de Produtos -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body p-0">
                        <div class="product-search-container px-3 py-2">
                            <input type="text" id="product-search" class="form-control" 
                                   placeholder="Buscar produtos por nome, SKU ou código de barras...">
                        </div>
                        <div class="p-3">
                            <div id="products-grid" class="products-grid">
                                <!-- Produtos serão listados aqui -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área do Carrinho -->
            <div class="col-md-4">
                <!-- Cliente -->
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Cliente</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#novoClienteModal">
                                <i class="bi bi-person-plus"></i> Novo Cliente
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="cliente-search-container">
                            <div class="input-group mb-3">
                                <input type="text" id="customerSearch" class="form-control" 
                                       placeholder="Buscar cliente por nome, telefone ou CPF...">
                                <button class="btn btn-outline-secondary" type="button" id="buscarCliente">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div id="clienteResults">
                                <!-- Resultados da busca aparecerão aqui -->
                            </div>
                        </div>
                        <div id="clienteSelecionado" class="mt-3" style="display: none;">
                            <h6>Cliente Selecionado:</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6 id="clienteNome" class="card-title"></h6>
                                    <p id="clienteInfo" class="card-text small"></p>
                                    <input type="hidden" id="customer_id" name="customer_id">
                                </div>
                            </div>
                            <button id="limparCliente" class="btn btn-sm btn-secondary mt-2" onclick="limparCliente()">
                                Limpar Seleção
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Carrinho -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Carrinho</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="cart-container p-3" style="z-index: 1000;">
                            <div id="cartItems">
                                <!-- Items do carrinho serão inseridos aqui -->
                            </div>
                        </div>
                        <div class="cart-summary p-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">R$ 0,00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Desconto:</span>
                                <div class="input-group" style="width: 150px;">
                                    <input type="number" id="discount" class="form-control" value="0" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between fw-bold mb-3">
                                <span>Total:</span>
                                <span id="total">R$ 0,00</span>
                            </div>
                            
                            <!-- Formas de Pagamento -->
                            <div class="mb-3">
                                <label class="form-label">Forma de Pagamento:</label>
                                <select class="form-select" id="payment_method">
                                    <option value="">Selecione...</option>
                                    <option value="money">Dinheiro</option>
                                    <option value="credit_card">Cartão de Crédito</option>
                                    <option value="debit_card">Cartão de Débito</option>
                                    <option value="pix">PIX</option>
                                </select>
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-primary w-100 mb-2" id="finalizarVenda">
                                    <i class="bi bi-check-circle"></i> Finalizar Venda
                                </button>
                                <button class="btn btn-outline-danger w-100" id="cancelarVenda">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let allProducts = [];
    let cart = [];
    let selectedCustomerId = null;

    // Carregar produtos
    function loadProducts() {
        $.get('/pdv/products')
            .done(function(products) {
                allProducts = products;
                displayProducts(products);
            })
            .fail(function(error) {
                console.error('Erro ao carregar produtos:', error);
                alert('Erro ao carregar produtos. Por favor, tente novamente.');
            });
    }

    // Exibição de produtos
    function displayProducts(products) {
        let html = '';
        products.forEach(product => {
            const imageUrl = product.image 
                ? `/imagens/produtos/${product.image}`
                : '/imagens/produtos/no-image.jpg';
                
            html += `
                <div class="product-card">
                    <div class="product-image">
                        <img src="${imageUrl}" alt="${product.name}" class="img-fluid">
                    </div>
                    <div class="product-info">
                        <h3>${product.name}</h3>
                        <div class="product-details">
                            <p class="product-sku">SKU: ${product.sku || 'N/A'}</p>
                            <p class="product-stock">Estoque: ${product.stock_quantity || 0}</p>
                            <p class="product-price">R$ ${product.price}</p>
                            <button class="btn btn-primary btn-add-cart" onclick="event.preventDefault(); addToCart(${product.id})">
                                <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (products.length === 0) {
            html = '<div class="col-12 text-center py-4">Nenhum produto encontrado</div>';
        }
        
        $('#products-grid').html(html);
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

    // Busca de clientes
    function searchCustomers() {
        const query = document.getElementById('customerSearch').value;
        const resultsDiv = document.getElementById('clienteResults');

        if (query.length < 2) {
            resultsDiv.classList.remove('show');
            resultsDiv.innerHTML = '';
            return;
        }

        fetch(`/pdv/search-customers?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(customer => {
                        const div = document.createElement('div');
                        div.className = 'customer-item';
                        div.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">${customer.name}</h6>
                                    <small>CPF: ${customer.cpf || 'N/A'} | Tel: ${customer.phone || 'N/A'}</small>
                                </div>
                                <button class="btn btn-sm btn-primary" 
                                    onclick="selectCustomer('${customer.id}', '${customer.name}', '${customer.cpf || ''}', '${customer.phone || ''}')">
                                    Selecionar
                                </button>
                            </div>
                        `;
                        resultsDiv.appendChild(div);
                    });
                    resultsDiv.classList.add('show');
                } else {
                    resultsDiv.innerHTML = '<div class="customer-item">Nenhum cliente encontrado</div>';
                    resultsDiv.classList.add('show');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar clientes:', error);
                resultsDiv.innerHTML = '<div class="customer-item">Erro ao buscar clientes</div>';
                resultsDiv.classList.add('show');
            });
    }

    // Adiciona listener para fechar a lista quando clicar fora
    document.addEventListener('click', function(event) {
        const resultsDiv = document.getElementById('clienteResults');
        const searchContainer = document.querySelector('.cliente-search-container');
        const searchInput = document.getElementById('customerSearch');
        
        if (!searchContainer.contains(event.target) && event.target !== searchInput) {
            resultsDiv.classList.remove('show');
        }
    });

    // Função para selecionar cliente
    function selectCustomer(id, name, cpf, phone) {
        document.getElementById('customer_id').value = id;
        document.getElementById('clienteSelecionado').style.display = 'block';
        document.getElementById('clienteInfo').innerHTML = `
            <div><strong>Nome:</strong> ${name}</div>
            <div><strong>CPF:</strong> ${cpf || 'N/A'}</div>
            <div><strong>Telefone:</strong> ${phone || 'N/A'}</div>
        `;
        document.getElementById('clienteResults').classList.remove('show');
        document.getElementById('customerSearch').value = '';
    }

    // Função para limpar cliente selecionado
    function limparCliente() {
        document.getElementById('customer_id').value = '';
        document.getElementById('clienteSelecionado').style.display = 'none';
        document.getElementById('clienteInfo').innerHTML = '';
        document.getElementById('customerSearch').value = '';
    }

    // Adicionar ao carrinho
    function addToCart(productId) {
        const product = allProducts.find(p => p.id === productId);
        if (!product) return;

        let existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            if (existingItem.quantity < existingItem.stock_quantity) {
                existingItem.quantity++;
                updateCart();
            } else {
                alert('Quantidade máxima atingida para este produto!');
            }
        } else {
            if (product.stock_quantity > 0) {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: 1,
                    stock_quantity: product.stock_quantity
                });
                updateCart();
            } else {
                alert('Produto sem estoque!');
            }
        }
    }

    // Atualizar carrinho
    function updateCart() {
        const cartContainer = $('#cartItems');
        let html = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const total = item.price * item.quantity;
            subtotal += total;

            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-0">${item.name}</h6>
                            <small class="text-muted">
                                R$ ${item.price} cada
                            </small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary decrease-quantity" type="button" data-index="${index}">-</button>
                            <input type="text" class="form-control text-center item-quantity" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary increase-quantity" type="button" data-index="${index}">+</button>
                        </div>
                        <div class="text-end">
                            <strong>R$ ${total.toFixed(2)}</strong>
                        </div>
                    </div>
                </div>
            `;
        });

        cartContainer.html(html);
        updateTotals(subtotal);
    }

    // Atualizar totais
    function updateTotals(subtotal) {
        $('#subtotal').text(`R$ ${subtotal.toFixed(2)}`);
        
        const discountPercent = parseFloat($('#discount').val() || 0);
        const discountValue = (subtotal * discountPercent) / 100;
        const total = subtotal - discountValue;
        
        $('#total').text(`R$ ${total.toFixed(2)}`);
    }

    // Eventos do carrinho
    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        updateCart();
    });

    $(document).on('click', '.decrease-quantity', function() {
        const index = $(this).data('index');
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
            updateCart();
        }
    });

    $(document).on('click', '.increase-quantity', function() {
        const index = $(this).data('index');
        if (cart[index].quantity < cart[index].stock_quantity) {
            cart[index].quantity++;
            updateCart();
        } else {
            alert('Quantidade máxima atingida para este produto!');
        }
    });

    $('#discount').on('input', function() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        updateTotals(subtotal);
    });

    // Finalizar Venda
    $('#finalizarVenda').click(function() {
        // Validações básicas
        if (cart.length === 0) {
            alert('Adicione produtos ao carrinho antes de finalizar a venda.');
            return;
        }

        const paymentMethod = $('#payment_method').val();
        if (!paymentMethod) {
            alert('Selecione uma forma de pagamento.');
            return;
        }

        // Preparar dados da venda
        const saleData = {
            customer_id: $('#customer_id').val() || null,
            items: cart.map(item => ({
                id: item.id,
                quantity: item.quantity,
                price: item.price
            })),
            discount: parseFloat($('#discount').val() || 0),
            payment_method: paymentMethod
        };

        // Desabilitar botão para evitar duplo clique
        const $button = $(this);
        $button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Processando...');

        // Enviar requisição
        $.ajax({
            url: '/pdv/finalize-sale',
            method: 'POST',
            data: saleData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Redirecionar para a página de detalhes da venda
                        window.location.href = response.redirect_url;
                    });
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.error || 'Erro ao finalizar a venda. Tente novamente.';
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: message
                });
            },
            complete: function() {
                // Reabilitar botão
                $button.prop('disabled', false).html('<i class="bi bi-check-circle"></i> Finalizar Venda');
            }
        });
    });

    // Cancelar Venda
    $('#cancelarVenda').click(function() {
        if (cart.length === 0) {
            return;
        }

        Swal.fire({
            title: 'Cancelar Venda?',
            text: 'Todos os itens do carrinho serão removidos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                updateCart();
                $('#customer_id').val('');
                $('#customerSearch').val('');
                $('#clienteSelecionado').hide();
                $('#payment_method').val('');
                $('#discount').val(0);
            }
        });
    });

    // Inicialização
    $(document).ready(function() {
        loadProducts();
        document.getElementById('customerSearch').addEventListener('input', searchCustomers);
    });
</script>
@endpush

@push('styles')
<style>
    body {
        padding-top: 60px; /* Espaço para o menu fixo */
    }

    .content-pdv {
        padding: 1rem;
        margin-top: 10px;
    }

    /* Header fixo */
    .header-pdv {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1000;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    /* Grid de produtos */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem 0;
    }

    .product-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .product-image {
        height: 160px;
        padding: 0.5rem;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-info {
        padding: 1rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-info h3 {
        font-size: 0.9rem;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
        height: 2.4em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-details {
        margin-top: auto;
    }

    .product-sku {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .product-stock {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0d6efd;
        margin-bottom: 0.5rem;
    }

    .btn-add-cart {
        width: 100%;
        padding: 0.375rem;
        font-size: 0.875rem;
    }

    /* Área do Carrinho */
    .cart-container {
        max-height: calc(100vh - 500px);
        overflow-y: auto;
        padding: 1rem;
    }

    .cart-item {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        background: #fff;
    }

    .cart-item:last-child {
        margin-bottom: 0;
    }

    .cart-item .item-quantity {
        width: 60px !important;
        text-align: center;
    }

    .cart-summary {
        background: #f8f9fa;
        padding: 1rem;
        border-top: 1px solid #dee2e6;
    }

    /* Botões */
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Cliente */
    .cliente-search-container {
        position: relative;
        z-index: 9999;
    }

    .cliente-search-container .input-group {
        margin-bottom: 0 !important;
    }

    #clienteResults {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        z-index: 10000;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 0;
        display: none;
    }

    #clienteResults.show {
        display: block;
    }

    .customer-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        background: white;
    }

    .customer-item:hover {
        background-color: #f8f9fa;
    }

    .customer-item:last-child {
        border-bottom: none;
    }

    #clienteSelecionado {
        background: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #dee2e6;
    }

    #clienteSelecionado h6 {
        color: #0d6efd;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    #clienteSelecionado #clienteInfo {
        font-size: 0.9rem;
        color: #666;
    }

    #clienteSelecionado #clienteInfo div {
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }

    #limparCliente {
        width: 100%;
        margin-top: 0.5rem;
    }

    .select-customer {
        padding: 0.25rem 0.5rem;
    }

    /* Ajuste para garantir que o dropdown fique sobre outros elementos */
    .card {
        position: relative;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .content-pdv {
            padding: 0.5rem;
        }
        
        .product-image {
            height: 150px;
        }
        
        .cart-container {
            max-height: none;
        }
    }

    /* Ajustes de Z-index */
    .sticky-top {
        z-index: 100 !important;
    }
    
    .cliente-search-container {
        position: relative;
        z-index: 1500 !important;
    }

    #clienteResults {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 2000 !important;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }

    #clienteResults.show {
        display: block;
    }

    .customer-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        background: white;
    }

    .customer-item:hover {
        background-color: #f8f9fa;
    }

    .customer-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
