// PDV JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se está na página do PDV
    if (!document.getElementById('pdv-container')) {
        return; // Se não estiver na página do PDV, não inicializa nada
    }

    // Estado global
    const state = {
        cart: [],
        products: [],
        selectedCustomer: null,
        categories: new Set(),
        discount: 0,
        paymentMethod: 'money'
    };

    // Cache de elementos DOM
    const elements = {
        productSearch: document.getElementById('product-search'),
        productsGrid: document.getElementById('products-grid'),
        cartItems: document.getElementById('cart-items'),
        cartSubtotal: document.getElementById('cart-subtotal'),
        cartTotal: document.getElementById('cart-total'),
        discountInput: document.getElementById('discount-input'),
        selectedCustomer: document.getElementById('selected-customer'),
        finishSaleBtn: document.getElementById('finish-sale'),
        holdSaleBtn: document.getElementById('hold-sale'),
        viewSalesBtn: document.getElementById('view-sales'),
        cancelSaleBtn: document.getElementById('cancel-sale')
    };

    // Verifica se todos os elementos necessários existem
    if (!elements.productSearch || !elements.productsGrid || !elements.cartItems) {
        console.warn('Elementos necessários do PDV não encontrados');
        return;
    }

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') { // Buscar cliente
            const customerSearchBtn = document.querySelector('[data-bs-target="#customerSearchModal"]');
            if (customerSearchBtn) customerSearchBtn.click();
        } else if (e.key === 'F3') { // Foco na busca
            elements.productSearch.focus();
        } else if (e.key === 'F4' && elements.finishSaleBtn) { // Finalizar venda
            elements.finishSaleBtn.click();
        } else if (e.key === 'F6' && elements.holdSaleBtn) { // Segurar venda
            elements.holdSaleBtn.click();
        } else if (e.key === 'F7' && elements.viewSalesBtn) { // Ver vendas
            elements.viewSalesBtn.click();
        } else if (e.key === 'F8' && elements.cancelSaleBtn) { // Cancelar venda
            elements.cancelSaleBtn.click();
        }
    });

    // Busca de produtos em tempo real
    elements.productSearch.addEventListener('input', debounce(function(e) {
        const query = e.target.value.toLowerCase();
        filterProducts(query);
    }, 300));

    function filterProducts(query) {
        const filteredProducts = state.products.filter(product => 
            product.name.toLowerCase().includes(query) ||
            (product.sku && product.sku.toLowerCase().includes(query)) ||
            (product.barcode && product.barcode.toLowerCase().includes(query))
        );
        renderProducts(filteredProducts);
    }

    // Renderização de produtos
    function renderProducts(products) {
        if (!elements.productsGrid) return;
        
        elements.productsGrid.innerHTML = products.map(product => `
            <div class="product-card" data-id="${product.id}" onclick="addToCart(${product.id})">
                <img src="${product.image || '/images/no-image.png'}" class="product-image" alt="${product.name}">
                <div class="product-name">${product.name}</div>
                <div class="product-price">${formatMoney(product.price)}</div>
                <div class="product-stock">Estoque: ${product.stock}</div>
            </div>
        `).join('');
    }

    // Gerenciamento do carrinho
    window.addToCart = function(productId) {
        const product = state.products.find(p => p.id === productId);
        if (!product) return;

        const cartItem = state.cart.find(item => item.product.id === productId);
        if (cartItem) {
            if (cartItem.quantity < product.stock) {
                cartItem.quantity++;
                showToast('Quantidade atualizada!', 'success');
            } else {
                showToast('Estoque insuficiente!', 'error');
            }
        } else {
            state.cart.push({
                product,
                quantity: 1
            });
            showToast('Produto adicionado!', 'success');
        }

        updateCart();
    }

    function updateCart() {
        if (!elements.cartItems) return;

        // Renderiza items
        elements.cartItems.innerHTML = state.cart.map((item, index) => `
            <div class="cart-item">
                <div class="cart-item-info">
                    <span class="cart-item-name">${item.product.name}</span>
                    <span class="cart-item-price">${formatMoney(item.product.price)}</span>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                </div>
                <button class="btn btn-sm btn-danger" onclick="removeItem(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `).join('');

        // Atualiza totais
        const totals = calculateTotals();
        if (elements.cartSubtotal) elements.cartSubtotal.textContent = formatMoney(totals.subtotal_amount);
        if (elements.cartTotal) elements.cartTotal.textContent = formatMoney(totals.total_amount);
    }

    window.updateQuantity = function(index, delta) {
        const item = state.cart[index];
        const newQuantity = item.quantity + delta;

        if (newQuantity > 0 && newQuantity <= item.product.stock) {
            item.quantity = newQuantity;
            updateCart();
            showToast('Quantidade atualizada!', 'success');
        } else {
            showToast('Quantidade inválida!', 'error');
        }
    }

    window.removeItem = function(index) {
        state.cart.splice(index, 1);
        updateCart();
        showToast('Item removido!', 'success');
    }

    // Cálculos
    function calculateTotals() {
        let subtotal = 0;
        state.cart.forEach(item => {
            subtotal += item.product.price * item.quantity;
        });

        const discountPercent = parseFloat(elements.discountInput?.value || 0);
        const discountAmount = (subtotal * discountPercent) / 100;
        const total = subtotal - discountAmount;

        return {
            subtotal_amount: subtotal,
            discount_percent: discountPercent,
            discount_amount: discountAmount,
            total_amount: total
        };
    }

    // Finalização da venda
    if (elements.finishSaleBtn) {
        elements.finishSaleBtn.addEventListener('click', function() {
            if (state.cart.length === 0) {
                showToast('Adicione produtos ao carrinho!', 'error');
                return;
            }

            if (!state.selectedCustomer) {
                showToast('Selecione um cliente!', 'error');
                return;
            }

            const paymentMethod = state.paymentMethod;
            if (!paymentMethod) {
                showToast('Por favor, selecione um método de pagamento.', 'error');
                return;
            }

            const totals = calculateTotals();
            const saleData = {
                customer_id: state.selectedCustomer.id,
                items: state.cart.map(item => ({
                    product_id: item.product.id,
                    quantity: item.quantity
                })),
                subtotal_amount: totals.subtotal_amount,
                discount_percent: totals.discount_percent,
                discount_amount: totals.discount_amount,
                total_amount: totals.total_amount,
                payment_method: paymentMethod
            };

            fetch('/sales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(saleData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Venda realizada com sucesso!', 'success');
                    // Limpa o carrinho
                    state.cart = [];
                    state.selectedCustomer = null;
                    updateCart();
                    // Atualiza a interface
                    if (elements.selectedCustomer) elements.selectedCustomer.textContent = '';
                    if (elements.discountInput) elements.discountInput.value = '';
                } else {
                    showToast(data.message || 'Erro ao finalizar venda', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao finalizar venda', 'error');
            });
        });
    }

    // Utilitários
    function formatMoney(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Carrega produtos inicialmente
    function loadProducts() {
        fetch('/pdv/products')
            .then(response => response.json())
            .then(data => {
                state.products = data;
                renderProducts(data);
            })
            .catch(error => {
                console.error('Erro ao carregar produtos:', error);
                showToast('Erro ao carregar produtos', 'error');
            });
    }

    // Inicializa
    loadProducts();
});
