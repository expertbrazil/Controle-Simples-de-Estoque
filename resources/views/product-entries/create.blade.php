@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nova Entrada de Produto</h1>
        <a href="{{ route('product-entries.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form id="entryForm" action="{{ route('product-entries.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div id="entriesList">
                            <!-- Os itens serão adicionados aqui dinamicamente -->
                        </div>

                        <div class="text-center mb-4">
                            <button type="button" class="btn btn-outline-primary" id="addEntry">
                                <i class="fas fa-plus"></i> Adicionar Produto
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Total Geral: <span id="grandTotal" class="text-primary">R$ 0,00</span></h4>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-check"></i> Registrar Entradas
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
            <h5 class="card-title mb-0">Produto</h5>
            <button type="button" class="btn btn-outline-danger btn-sm remove-entry">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="row g-3">
            <!-- Produto -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Produto</label>
                    <select class="form-control product-search" name="entries[0][product_id]" required>
                        <option value="">Digite para buscar...</option>
                    </select>
                    <div class="invalid-feedback">Selecione um produto</div>
                </div>
            </div>

            <!-- Preço de Compra -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Valor da Compra</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" 
                               class="form-control money purchase-price" 
                               name="entries[0][purchase_price]" 
                               required>
                    </div>
                </div>
            </div>

            <!-- Imposto -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Imposto</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control percentage tax-percentage" 
                               name="entries[0][tax_percentage]" 
                               value="0,00">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <!-- Frete -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Frete</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" 
                               class="form-control money freight-cost" 
                               name="entries[0][freight_cost]" 
                               value="0,00">
                    </div>
                </div>
            </div>

            <!-- Peso -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Peso</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control weight weight-kg" 
                               name="entries[0][weight_kg]" 
                               value="0,000">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
            </div>

            <!-- Custo Unitário -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Custo Unitário</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" 
                               class="form-control money unit-cost" 
                               name="entries[0][unit_cost]" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Quantidade -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Quantidade</label>
                    <input type="number" 
                           class="form-control quantity" 
                           name="entries[0][quantity]" 
                           value="1" 
                           min="1" 
                           required>
                </div>
            </div>

            <!-- Total -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Total</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="text" 
                               class="form-control money entry-total" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">Observações</label>
                    <textarea class="form-control" 
                              name="entries[0][notes]" 
                              rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-selection {
        height: 38px !important;
        padding: 5px !important;
    }
    .select2-selection__arrow {
        height: 37px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    let entryCount = 0;

    // Função para formatar número como moeda
    function formatMoney(value) {
        if (typeof value !== 'number') return '0,00';
        return value.toFixed(2).replace('.', ',');
    }

    // Função para formatar peso
    function formatWeight(value) {
        if (typeof value !== 'number' || isNaN(value)) return '0,000';
        return value.toFixed(3).replace('.', ',');
    }

    // Função para converter string formatada em número
    function parseFormattedNumber(value, decimals = 2) {
        if (!value) return 0;
        const cleanValue = value.toString().replace(/\./g, '').replace(',', '.');
        const parsedValue = parseFloat(cleanValue);
        return isNaN(parsedValue) ? 0 : Number(parsedValue.toFixed(decimals));
    }

    // Função para calcular os valores de uma entrada
    function calculateEntryValues(entryDiv) {
        const purchasePrice = parseFormattedNumber(entryDiv.find('.purchase-price').val());
        const taxPercentage = parseFormattedNumber(entryDiv.find('.tax-percentage').val());
        const freightCost = parseFormattedNumber(entryDiv.find('.freight-cost').val());
        const weightKg = parseFormattedNumber(entryDiv.find('.weight-kg').val(), 3);
        const quantity = parseInt(entryDiv.find('.quantity').val()) || 1;

        // Cálculo do imposto
        const taxValue = purchasePrice * (taxPercentage / 100);

        // Cálculo do frete por peso
        const freightTotal = freightCost * weightKg;

        // Cálculo do custo unitário (por unidade)
        const unitCost = purchasePrice + taxValue + freightTotal;

        // Cálculo do total
        const total = unitCost * quantity;

        // Atualiza os campos
        entryDiv.find('.unit-cost').val(formatMoney(unitCost));
        entryDiv.find('.entry-total').val(formatMoney(total));

        return total;
    }

    // Função para calcular o total geral
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.entry-item').each(function() {
            grandTotal += calculateEntryValues($(this));
        });
        $('#grandTotal').text('R$ ' + formatMoney(grandTotal));
    }

    // Função para inicializar plugins em uma entrada
    function initializeEntryPlugins(entryDiv) {
        // Select2 para busca de produtos
        entryDiv.find('.product-search').select2({
            theme: 'bootstrap-5',
            placeholder: 'Digite para buscar...',
            allowClear: true,
            minimumInputLength: 2,
            language: {
                inputTooShort: function() {
                    return 'Digite pelo menos 2 caracteres para buscar...';
                },
                noResults: function() {
                    return 'Nenhum produto encontrado';
                },
                searching: function() {
                    return 'Buscando...';
                }
            },
            ajax: {
                url: '{{ route("api.products.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name + (item.sku ? ' (' + item.sku + ')' : ''),
                                weight: item.weight_kg,
                                lastEntry: item.last_entry
                            };
                        })
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            const data = e.params.data;
            const entryDiv = $(this).closest('.entry-item');
            
            // Preenche o peso
            entryDiv.find('.weight-kg').val(formatWeight(parseFloat(data.weight_kg)));
            
            // Se houver última entrada, preenche os valores
            if (data.lastEntry) {
                entryDiv.find('.purchase-price').val(data.lastEntry.purchase_price);
                entryDiv.find('.tax-percentage').val(data.lastEntry.tax_percentage);
                entryDiv.find('.freight-cost').val(data.lastEntry.freight_cost);
            } else {
                entryDiv.find('.purchase-price').val('0,00');
                entryDiv.find('.tax-percentage').val('0,00');
                entryDiv.find('.freight-cost').val('0,00');
            }
            
            // Recalcula os valores
            calculateEntryValues(entryDiv);
            calculateGrandTotal();
        });

        // Máscaras para campos monetários e percentuais
        entryDiv.find('.money').mask('#.##0,00', { 
            reverse: true,
            onChange: function() {
                calculateEntryValues(entryDiv);
                calculateGrandTotal();
            }
        });
        
        entryDiv.find('.percentage').mask('##0,00', { 
            reverse: true,
            onChange: function() {
                calculateEntryValues(entryDiv);
                calculateGrandTotal();
            }
        });
        
        entryDiv.find('.weight').mask('#.###0,000', { 
            reverse: true,
            onChange: function() {
                calculateEntryValues(entryDiv);
                calculateGrandTotal();
            }
        });

        // Evento para quantidade
        entryDiv.find('.quantity').on('input', function() {
            calculateEntryValues(entryDiv);
            calculateGrandTotal();
        });
    }

    // Função para adicionar nova entrada
    function addEntry() {
        entryCount++;
        const template = document.getElementById('entryTemplate');
        const clone = template.content.cloneNode(true);
        
        // Atualiza os índices
        $(clone).find('[name^="entries[0]"]').each(function() {
            const name = $(this).attr('name').replace('entries[0]', `entries[${entryCount}]`);
            $(this).attr('name', name);
        });
        
        // Adiciona ao formulário
        $('#entriesList').append(clone);
        
        // Inicializa os plugins na nova entrada
        const newEntry = $('#entriesList .entry-item').last();
        initializeEntryPlugins(newEntry);
        
        // Atualiza o total
        calculateGrandTotal();
    }

    // Botão para adicionar entrada
    $('#addEntry').click(addEntry);

    // Remover entrada
    $(document).on('click', '.remove-entry', function() {
        if ($('.entry-item').length > 1) {
            $(this).closest('.entry-item').remove();
            calculateGrandTotal();
        }
    });

    // Validação do formulário
    $('#entryForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validar se todos os produtos foram selecionados
        let isValid = true;
        $('.product-search').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            alert('Por favor, selecione todos os produtos antes de continuar.');
            return;
        }
        
        // Submit do formulário
        this.submit();
    });

    // Adiciona primeira entrada ao carregar
    addEntry();
});
</script>
@endpush
