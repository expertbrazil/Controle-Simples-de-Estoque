// PDV JavaScript
document.addEventListener('DOMContentLoaded', function() {
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

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2') { // Buscar cliente
            document.querySelector('[data-bs-target="#customerSearchModal"]').click();
        } else if (e.key === 'F3') { // Foco na busca
            elements.productSearch.focus();
        } else if (e.key === 'F4') { // Finalizar venda
            elements.finishSaleBtn.click();
        } else if (e.key === 'F6') { // Segurar venda
            elements.holdSaleBtn.click();
        } else if (e.key === 'F7') { // Ver vendas
            elements.viewSalesBtn.click();
        } else if (e.key === 'F8') { // Cancelar venda
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
            product.sku.toLowerCase().includes(query) ||
            product.barcode.toLowerCase().includes(query)
        );
        renderProducts(filteredProducts);
    }

    // Renderização de produtos
    function renderProducts(products) {
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
    function addToCart(productId) {
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
        elements.cartSubtotal.textContent = formatMoney(totals.subtotal_amount);
        elements.cartTotal.textContent = formatMoney(totals.total_amount);
    }

    function updateQuantity(index, delta) {
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

    function removeItem(index) {
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

        const discountPercent = parseFloat(elements.discountInput.value || 0);
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
                state.cart = [];
                updateCart();
                state.selectedCustomer = null;
                elements.selectedCustomer.innerHTML = '';
                elements.discountInput.value = '0';
                calculateTotals();
            } else {
                showToast(data.message || 'Erro ao realizar venda.', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao realizar venda.', 'error');
        });
    });

    // Cadastro de Cliente
    document.getElementById('salvarNovoCliente').addEventListener('click', async function() {
        const form = document.getElementById('novoClienteForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/customers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Erro ao cadastrar cliente');
            }

            const customer = await response.json();
            
            // Atualiza o estado
            state.selectedCustomer = customer;
            
            // Atualiza a UI
            elements.selectedCustomer.innerHTML = `
                <i class="bi bi-person-circle"></i>
                <span>${customer.name}</span>
            `;
            
            // Fecha o modal
            bootstrap.Modal.getInstance(document.getElementById('novoClienteModal')).hide();
            
            // Limpa o formulário
            form.reset();
            
            showToast('Cliente cadastrado com sucesso!', 'success');
        } catch (error) {
            console.error('Erro:', error);
            showToast(error.message, 'error');
        }
    });

    // Busca de CEP
    document.getElementById('cep').addEventListener('blur', async function() {
        const cep = this.value.replace(/\D/g, '');
        
        if (cep.length !== 8) return;

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();

            if (data.erro) {
                throw new Error('CEP não encontrado');
            }

            // Preenche os campos
            document.getElementById('endereco').value = data.logradouro;
            document.getElementById('bairro').value = data.bairro;
            document.getElementById('cidade').value = data.localidade;
            document.getElementById('uf').value = data.uf;

            // Foca no campo número
            document.getElementById('numero').focus();
        } catch (error) {
            console.error('Erro:', error);
            showToast('Erro ao buscar CEP', 'error');
        }
    });

    // Máscaras de input
    const masks = {
        cpf: '000.000.000-00',
        phone: '(00) 00000-0000',
        cep: '00000-000'
    };

    for (const [id, mask] of Object.entries(masks)) {
        const input = document.getElementById(id);
        if (input) {
            IMask(input, {
                mask: mask
            });
        }
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
        toast.className = `toast toast-${type} fade-in`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
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
    async function loadProducts() {
        try {
            const response = await fetch('/api/products');
            if (!response.ok) throw new Error('Erro ao carregar produtos');
            
            state.products = await response.json();
            state.categories = new Set(state.products.map(p => p.category));
            
            renderProducts(state.products);
            renderCategories();
        } catch (error) {
            console.error('Erro:', error);
            showToast('Erro ao carregar produtos!', 'error');
        }
    }

    loadProducts();
});
