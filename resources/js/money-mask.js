document.addEventListener('DOMContentLoaded', function() {
    const moneyInputs = document.querySelectorAll('.money');
    
    function formatMoney(value) {
        // Remove tudo que não é número e ponto
        value = value.replace(/[^\d.]/g, '');
        
        // Converte para número e garante 2 casas decimais
        value = parseFloat(value || 0).toFixed(2);
        
        // Formata como moeda brasileira
        return new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }
    
    function unformatMoney(value) {
        // Remove tudo que não é número e ponto
        return value.replace(/[^\d.]/g, '');
    }
    
    moneyInputs.forEach(function(input) {
        // Formata o valor inicial se existir
        if (input.value) {
            input.value = formatMoney(input.value);
        }
        
        input.addEventListener('input', function(e) {
            const value = unformatMoney(e.target.value);
            e.target.value = formatMoney(value);
        });

        input.addEventListener('blur', function(e) {
            if (e.target.value.trim() === '') {
                e.target.value = formatMoney('0');
            } else {
                const value = unformatMoney(e.target.value);
                e.target.value = formatMoney(value);
            }
        });
    });

    // Adiciona listener para o formulário
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            moneyInputs.forEach(function(input) {
                // Remove a formatação antes de enviar
                const unformattedValue = unformatMoney(input.value);
                // Cria um input hidden com o valor sem formatação
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = input.name;
                hiddenInput.value = unformattedValue;
                // Substitui o input original
                input.name = input.name + '_formatted';
                form.appendChild(hiddenInput);
            });
        });
    }
});
