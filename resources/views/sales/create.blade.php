@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body p-0">
                    <div class="product-search-container px-3">
                        <input type="text" id="product-search" class="form-control" 
                               placeholder="Buscar produtos por nome, SKU ou código de barras...">
                    </div>
                    <div class="p-3">
                        <div id="products-grid" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            <!-- Produtos serão listados aqui -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
                    <div class="input-group mb-3">
                        <input type="text" id="clienteSearch" class="form-control" placeholder="Buscar cliente por nome, telefone ou CPF...">
                        <button class="btn btn-outline-secondary" type="button" id="buscarCliente">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div id="clienteResults" class="list-group" style="max-height: 200px; overflow-y: auto;">
                        <!-- Resultados da busca aparecerão aqui -->
                    </div>
                    <div id="clienteSelecionado" class="mt-3" style="display: none;">
                        <h6>Cliente Selecionado:</h6>
                        <div class="card">
                            <div class="card-body">
                                <h6 id="clienteNome" class="card-title"></h6>
                                <p id="clienteInfo" class="card-text small"></p>
                                <input type="hidden" id="clienteId" name="customer_id">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Carrinho</h5>
                </div>
                <div class="card-body p-0">
                    <div class="cart-container p-3">
                        <div id="cartItems">
                            <!-- Items do carrinho serão inseridos aqui -->
                        </div>
                    </div>
                    <div class="cart-summary p-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal" class="subtotal">R$ 0,00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Desconto:</span>
                            <div class="input-group" style="width: 150px;">
                                <input type="number" class="form-control discount-input" value="0" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span id="total" class="total">R$ 0,00</span>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary w-100 mb-2" id="finalizarVenda">
                                <i class="bi bi-check-circle"></i> Finalizar Venda
                            </button>
                            <div class="btn-group w-100">
                                <button class="btn btn-outline-secondary" id="holdSale">
                                    <i class="bi bi-clock-history"></i> Segurar
                                </button>
                                <button class="btn btn-outline-secondary" id="viewSales">
                                    <i class="bi bi-list"></i> Ver Vendas
                                </button>
                                <button class="btn btn-outline-danger" id="cancelSale">
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

<!-- Modal Novo Cliente -->
<div class="modal fade" id="novoClienteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="novoClienteForm">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-control" name="phone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CPF</label>
                        <input type="text" class="form-control" name="cpf">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarCliente">Salvar</button>
            </div>
        </div>
    </div>
</div>

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
                const imageUrl = product.thumbnail 
                    ? `/storage/app/public/${product.thumbnail}`
                    : '/images/nova_rosa_callback_ok.webp';
                    
                html += `
                    <div class="col">
                        <div class="card h-100 product-card">
                            <img src="${imageUrl}" class="card-img-top product-img" alt="${product.name}">
                            <div class="card-body">
                                <h6 class="card-title">${product.name}</h6>
                                <div class="product-sku mb-1">SKU: ${product.sku || 'N/A'}</div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="product-stock">Estoque: ${product.stock || 0}</div>
                                    <div class="product-price">${formatMoney(product.price)}</div>
                                </div>
                                <button class="btn btn-primary btn-sm w-100 add-to-cart" 
                                        data-product='${JSON.stringify(product)}'
                                        ${product.stock <= 0 ? 'disabled' : ''}>
                                    <i class="bi bi-cart-plus"></i> Adicionar
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

        // Adicionar ao carrinho
        $(document).on('click', '.add-to-cart', function() {
            const product = $(this).data('product');
            addToCart(product);
        });

        function addToCart(product) {
            let existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++;
                } else {
                    alert('Quantidade máxima atingida para este produto!');
                    return;
                }
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: 1,
                    stock: product.stock
                });
            }
            
            updateCart();
        }

        // Atualizar carrinho
        function updateCart() {
            let html = '';
            let subtotal = 0;
            
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                html += `
                    <div class="cart-item">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="fw-500">${item.name}</div>
                            <div class="cart-item-price">${formatMoney(itemTotal)}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">${formatMoney(item.price)} x ${item.quantity}</div>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-secondary btn-quantity decrease-qty" data-index="${index}">-</button>
                                <span class="cart-item-quantity">${item.quantity}</span>
                                <button class="btn btn-outline-secondary btn-quantity increase-qty" data-index="${index}">+</button>
                                <button class="btn btn-outline-danger btn-quantity remove-item" data-index="${index}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#cartItems').html(html);
            updateTotals(subtotal);
        }

        // Atualizar totais
        function updateTotals(subtotal) {
            const discountPercent = parseFloat($('.discount-input').val()) || 0;
            const discountAmount = (subtotal * discountPercent) / 100;
            const total = subtotal - discountAmount;

            $('#subtotal').text(formatMoney(subtotal));
            $('#total').text(formatMoney(total));
        }

        // Eventos do carrinho
        $(document).on('click', '.increase-qty', function() {
            const index = $(this).data('index');
            if (cart[index].quantity < cart[index].stock) {
                cart[index].quantity++;
                updateCart();
            }
        });

        $(document).on('click', '.decrease-qty', function() {
            const index = $(this).data('index');
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
                updateCart();
            }
        });

        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            cart.splice(index, 1);
            updateCart();
        });

        // Desconto
        $('.discount-input').on('input', function() {
            let value = parseFloat($(this).val());
            if (isNaN(value) || value < 0) {
                $(this).val(0);
                value = 0;
            } else if (value > 100) {
                $(this).val(100);
                value = 100;
            }
            
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            updateTotals(subtotal);
        });

        // Finalizar venda
        $('#finalizarVenda').click(function() {
            if (cart.length === 0) {
                alert('Adicione produtos ao carrinho para finalizar a venda.');
                return;
            }

            const saleData = {
                items: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: formatNumber(item.price)
                })),
                discount_percent: parseFloat($('.discount-input').val()) || 0,
                total: formatNumber(parseFloat($('#total').text().replace(/[^0-9,-]/g, '').replace(',', '.')))
            };

            $.post('/sales', saleData)
                .done(function(response) {
                    alert('Venda finalizada com sucesso!');
                    cart = [];
                    updateCart();
                })
                .fail(function(error) {
                    alert('Erro ao finalizar a venda. Tente novamente.');
                });
        });

        // Cancelar venda
        $('#cancelarVenda').click(function() {
            if (confirm('Deseja realmente cancelar a venda?')) {
                cart = [];
                updateCart();
            }
        });

        // Segurar venda
        $('#segurarVenda').click(function() {
            alert('Funcionalidade em desenvolvimento');
        });

        // Ver vendas
        $('#verVendas').click(function() {
            window.location.href = '/sales';
        });

        function formatProduct(product) {
            if (!product.id) return product.text;
            
            var $container = $(
                `<div class="select2-result-product d-flex align-items-center">
                    <img src="/storage/app/public/produtos/${product.image}" class="product-img me-2" style="width: 50px; height: 50px; object-fit: cover;" />
                    <div>
                        <div class="product-name">${product.text}</div>
                        <div class="product-details">
                            <small>Código: ${product.code}</small><br>
                            <small>Preço: R$ ${product.price}</small><br>
                            <small>Estoque: ${product.stock}</small>
                        </div>
                    </div>
                </div>`
            );
            
            return $container;
        }

        function formatProductSelection(product) {
            if (!product.id) return product.text;
            return $(`
                <div class="d-flex align-items-center">
                    <img src="/storage/app/public/produtos/${product.image}" class="product-img me-2" style="width: 30px; height: 30px; object-fit: cover;" />
                    <span>${product.text}</span>
                </div>
            `);
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
