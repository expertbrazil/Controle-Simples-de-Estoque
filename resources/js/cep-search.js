/**
 * CEP Search functionality
 * Provides address lookup using ViaCEP API
 */

class CepSearch {
    constructor(config = {}) {
        // Default field IDs
        this.fields = {
            cep: '#cep',
            rua: '#rua',
            bairro: '#bairro',
            cidade: '#cidade',
            uf: '#uf',
            ...config
        };

        this.bindEvents();
    }

    bindEvents() {
        const cepField = $(this.fields.cep);
        if (cepField.length) {
            cepField.on('blur', () => this.handleCepBlur());
        }
    }

    clearForm() {
        $(this.fields.rua).val('');
        $(this.fields.bairro).val('');
        $(this.fields.cidade).val('');
        $(this.fields.uf).val('');
    }

    setLoadingState() {
        $(this.fields.rua).val('...buscando informações');
        $(this.fields.bairro).val('...buscando informações');
        $(this.fields.cidade).val('...buscando informações');
        $(this.fields.uf).val('...buscando informações');
    }

    handleCepBlur() {
        // Get CEP value and remove non-digits
        let cep = $(this.fields.cep).val().replace(/\D/g, '');

        // Validate CEP
        if (cep !== '') {
            let validacep = /^[0-9]{8}$/;

            if (validacep.test(cep)) {
                this.setLoadingState();
                this.searchCep(cep);
            } else {
                this.clearForm();
                alert('Formato de CEP inválido.');
            }
        } else {
            this.clearForm();
        }
    }

    searchCep(cep) {
        $.getJSON(`https://viacep.com.br/ws/${cep}/json/?callback=?`, (data) => {
            if (!('erro' in data)) {
                $(this.fields.rua).val(data.logradouro);
                $(this.fields.bairro).val(data.bairro);
                $(this.fields.cidade).val(data.localidade);
                $(this.fields.uf).val(data.uf);
            } else {
                this.clearForm();
                alert('CEP não encontrado.');
            }
        });
    }
}

// Export for use in other files
window.CepSearch = CepSearch;
