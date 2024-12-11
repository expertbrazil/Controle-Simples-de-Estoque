@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nova Entrada de Produto</h1>
        <a href="{{ route('product-entries.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form id="entryForm" action="{{ route('product-entries.store') }}" method="POST">
                        @csrf
                        
                        <div id="entriesList">
                            <!-- Os itens serão adicionados aqui dinamicamente -->
                        </div>

                        <div class="text-center mb-4">
                            <button type="button" class="btn btn-outline-primary" id="addEntry">
                                <i class="bi bi-plus-lg"></i> Adicionar Produto
                            </button>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-check-lg"></i> Registrar Entradas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para novo item -->
<template id="entryTemplate">
    <div class="entry-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h5 class="card-title mb-0">Produto <span class="entry-number"></span></h5>
            <button type="button" class="btn btn-outline-danger btn-sm remove-entry">
                <i class="bi bi-trash"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Produto</label>
                    <input type="text" class="form-control product-search" placeholder="Digite para buscar...">
                    <input type="hidden" name="entries[0][product_id]" class="product-id">
                    <div class="invalid-feedback">Selecione um produto</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Preço de Compra</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money" name="entries[0][purchase_price]" required>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Preço de Custo</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" class="form-control money" name="entries[0][cost_price]" required>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Quantidade</label>
                    <input type="number" class="form-control" name="entries[0][quantity]" value="1" min="1" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">Observações</label>
                    <textarea class="form-control" name="entries[0][notes]" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const entriesList = document.getElementById('entriesList');
    const addEntryBtn = document.getElementById('addEntry');
    const entryTemplate = document.getElementById('entryTemplate');
    const submitBtn = document.getElementById('submitBtn');
    let entryCount = 0;

    // Função para inicializar a busca de produtos
    function initializeProductSearch(container) {
        const input = container.querySelector('.product-search');
        const productIdInput = container.querySelector('.product-id');
        
        $(input).autocomplete({
            source: function(request, response) {
                $.get('{{ route("product-entries.search-products") }}', {
                    q: request.term
                }).done(function(data) {
                    response(data.map(function(item) {
                        return {
                            label: `${item.name} (SKU: ${item.sku}) - Estoque: ${item.stock_quantity}`,
                            value: item.name,
                            item: item
                        };
                    }));
                });
            },
            minLength: 2,
            select: function(event, ui) {
                productIdInput.value = ui.item.item.id;
                input.classList.remove('is-invalid');
                input.value = ui.item.item.name;
                return false;
            }
        });
    }

    // Função para atualizar os índices dos campos
    function updateFieldIndexes() {
        document.querySelectorAll('.entry-item').forEach((item, index) => {
            item.querySelectorAll('[name^="entries["]').forEach(field => {
                field.name = field.name.replace(/entries\[\d+\]/, `entries[${index}]`);
            });
            item.querySelector('.entry-number').textContent = index + 1;
        });
    }

    // Adicionar novo item
    addEntryBtn.addEventListener('click', function() {
        const newEntry = entryTemplate.content.cloneNode(true);
        entriesList.appendChild(newEntry);
        
        const container = entriesList.lastElementChild;
        initializeProductSearch(container);
        
        // Inicializar máscara de moeda
        $(container).find('.money').mask('#.##0,00', {
            reverse: true
        });
        
        updateFieldIndexes();
        entryCount++;
    });

    // Remover item
    entriesList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-entry')) {
            e.target.closest('.entry-item').remove();
            updateFieldIndexes();
            entryCount--;
        }
    });

    // Adicionar primeiro item automaticamente
    addEntryBtn.click();

    // Submissão do formulário
    document.getElementById('entryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar se todos os produtos foram selecionados
        let isValid = true;
        this.querySelectorAll('.product-id').forEach(input => {
            if (!input.value) {
                input.previousElementSibling.classList.add('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            alert('Por favor, selecione todos os produtos antes de continuar.');
            return;
        }

        // Desabilitar botão de submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processando...';

        // Preparar dados do formulário
        const formData = new FormData(this);
        const entries = [];
        
        // Agrupar entradas
        const entriesData = {};
        for (const [key, value] of formData.entries()) {
            const match = key.match(/entries\[(\d+)\]\[([^\]]+)\]/);
            if (match) {
                const [, index, field] = match;
                entriesData[index] = entriesData[index] || {};
                
                if (field === 'purchase_price' || field === 'cost_price') {
                    // Converter valores monetários
                    entriesData[index][field] = parseFloat(value.replace(/\./g, '').replace(',', '.'));
                } else {
                    entriesData[index][field] = value;
                }
            }
        }
        
        // Converter para array
        const entriesArray = Object.values(entriesData);

        // Enviar dados
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ entries: entriesArray })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            }
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao salvar as entradas. Por favor, tente novamente.');
            
            // Reabilitar botão de submit
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Registrar Entradas';
        });
    });
});
</script>
@endpush
