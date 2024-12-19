$(document).ready(function() {
    // Máscara para campos monetários (R$ 0,00)
    $('.money').mask('#.##0,00', {
        reverse: true,
        placeholder: '0,00'
    });

    // Máscara para campos percentuais (0,00%)
    $('.percentage').mask('##0,00', {
        reverse: true,
        placeholder: '0,00'
    });

    // Máscara para campos decimais (0,000)
    $('.decimal').mask('##0,000', {
        reverse: true,
        placeholder: '0,000'
    });

    // Atualiza preços quando os campos são alterados
    $('#last_purchase_price, #tax_percentage, #freight_cost, #consumer_markup, #distributor_markup').on('change', function() {
        updatePrices();
    });

    function updatePrices() {
        // Obtém os valores dos campos
        let purchasePrice = parseFloat($('#last_purchase_price').val().replace('.', '').replace(',', '.')) || 0;
        let taxPercentage = parseFloat($('#tax_percentage').val().replace(',', '.')) || 0;
        let freightCost = parseFloat($('#freight_cost').val().replace('.', '').replace(',', '.')) || 0;
        let consumerMarkup = parseFloat($('#consumer_markup').val().replace(',', '.')) || 0;
        let distributorMarkup = parseFloat($('#distributor_markup').val().replace(',', '.')) || 0;

        // Calcula o valor dos impostos
        let taxAmount = purchasePrice * (taxPercentage / 100);

        // Calcula o custo unitário
        let unitCost = purchasePrice + taxAmount + freightCost;

        // Calcula os preços de venda
        let consumerPrice = unitCost * (1 + (consumerMarkup / 100));
        let distributorPrice = unitCost * (1 + (distributorMarkup / 100));

        // Exibe os resultados formatados
        $('#unit_cost').val(formatCurrency(unitCost));
        $('#consumer_price').val(formatCurrency(consumerPrice));
        $('#distributor_price').val(formatCurrency(distributorPrice));
    }

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Salvar Categoria via AJAX
    $('#quickCategoryForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        
        // Desabilita o botão e mostra loading
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Adiciona a nova categoria ao select
                    var newOption = new Option(response.category.name, response.category.id, true, true);
                    $('#category_id').append(newOption).trigger('change');
                    
                    // Limpa o formulário e fecha o modal
                    form[0].reset();
                    $('#categoryModal').modal('hide');
                    
                    toastr.success('Categoria cadastrada com sucesso!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(field) {
                        var input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    });
                }
                toastr.error('Erro ao cadastrar categoria');
            },
            complete: function() {
                // Reabilita o botão e remove loading
                submitButton.prop('disabled', false).html('Salvar');
            }
        });
    });

    // Salvar Marca via AJAX
    $('#quickBrandForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = form.find('button[type="submit"]');
        
        // Desabilita o botão e mostra loading
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Adiciona a nova marca ao select
                    var newOption = new Option(response.brand.name, response.brand.id, true, true);
                    $('#brand_id').append(newOption).trigger('change');
                    
                    // Limpa o formulário e fecha o modal
                    form[0].reset();
                    $('#brandModal').modal('hide');
                    
                    toastr.success('Marca cadastrada com sucesso!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(field) {
                        var input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    });
                }
                toastr.error('Erro ao cadastrar marca');
            },
            complete: function() {
                // Reabilita o botão e remove loading
                submitButton.prop('disabled', false).html('Salvar');
            }
        });
    });
});
