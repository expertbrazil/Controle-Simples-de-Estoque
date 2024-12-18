@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Novo Produto
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" 
                                           name="sku" 
                                           value="{{ old('sku') }}" 
                                           required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Selecione uma categoria</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="brand_id" class="form-label">Marca <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                        <option value="">Selecione uma marca</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#brandModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="supplier_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                        <option value="">Selecione um fornecedor</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nome_display }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFornecedor">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="last_purchase_price" class="form-label">Último Preço de Compra <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('last_purchase_price') is-invalid @enderror" 
                                       id="last_purchase_price" 
                                       name="last_purchase_price" 
                                       value="{{ old('last_purchase_price') }}" 
                                       required>
                                @error('last_purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tax_percentage" class="form-label">Percentual de Imposto <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('tax_percentage') is-invalid @enderror" 
                                       id="tax_percentage" 
                                       name="tax_percentage" 
                                       value="{{ old('tax_percentage') }}" 
                                       required>
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="freight_cost" class="form-label">Custo de Frete <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('freight_cost') is-invalid @enderror" 
                                       id="freight_cost" 
                                       name="freight_cost" 
                                       value="{{ old('freight_cost') }}" 
                                       required>
                                @error('freight_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="weight_kg" class="form-label">Peso (kg) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('weight_kg') is-invalid @enderror" 
                                       id="weight_kg" 
                                       name="weight_kg" 
                                       value="{{ old('weight_kg') }}" 
                                       required>
                                @error('weight_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consumer_markup" class="form-label">Markup Consumidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('consumer_markup') is-invalid @enderror" 
                                       id="consumer_markup" 
                                       name="consumer_markup" 
                                       value="{{ old('consumer_markup') }}" 
                                       required>
                                @error('consumer_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_markup" class="form-label">Markup Distribuidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('distributor_markup') is-invalid @enderror" 
                                       id="distributor_markup" 
                                       name="distributor_markup" 
                                       value="{{ old('distributor_markup') }}" 
                                       required>
                                @error('distributor_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="consumer_price" class="form-label">Preço Consumidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('consumer_price') is-invalid @enderror" 
                                       id="consumer_price" 
                                       name="consumer_price" 
                                       value="{{ old('consumer_price') }}" 
                                       required>
                                @error('consumer_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_price" class="form-label">Preço Distribuidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('distributor_price') is-invalid @enderror" 
                                       id="distributor_price" 
                                       name="distributor_price" 
                                       value="{{ old('distributor_price') }}" 
                                       required>
                                @error('distributor_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="image" class="form-label">Imagem do Produto</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Modal Categoria -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickCategoryForm" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Nome da Categoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="category_status" name="status" value="1" checked>
                            <label class="form-check-label" for="category_status">
                                Ativo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Marca -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel">Nova Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickBrandForm" action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Nome da Marca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="brand_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="brand_status" name="status" value="1" checked>
                            <label class="form-check-label" for="brand_status">
                                Ativo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Fornecedor -->
<div class="modal fade" id="modalFornecedor" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-end">
        <div class="modal-content min-vh-100">
            <div class="modal-header">
                <h5 class="modal-title">Novo Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFornecedor" action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="tipo_pessoa" class="form-label">Tipo de Pessoa</label>
                            <select class="form-select @error('tipo_pessoa') is-invalid @enderror" id="tipo_pessoa" name="tipo_pessoa">
                                <option value="F" {{ old('tipo_pessoa') == 'F' ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="J" {{ old('tipo_pessoa') == 'J' ? 'selected' : '' }}>Pessoa Jurídica</option>
                            </select>
                            @error('tipo_pessoa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="razao_social" class="form-label">Razão Social</label>
                            <input type="text" class="form-control @error('razao_social') is-invalid @enderror" id="razao_social" name="razao_social" value="{{ old('razao_social') }}">
                            @error('razao_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="documento" class="form-label">CPF/CNPJ</label>
                            <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value="{{ old('documento') }}">
                            @error('documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="phone" class="form-label">Telefone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="whatsapp" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep') }}">
                            @error('cep')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="rua" class="form-label">Rua</label>
                            <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua" value="{{ old('rua') }}">
                            @error('rua')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero') }}">
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento') }}">
                            @error('complemento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro') }}">
                            @error('bairro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade') }}">
                            @error('cidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="uf" class="form-label">UF</label>
                            <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2">
                            @error('uf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nome_contato" class="form-label">Nome do Contato</label>
                            <input type="text" class="form-control @error('nome_contato') is-invalid @enderror" id="nome_contato" name="nome_contato" value="{{ old('nome_contato') }}">
                            @error('nome_contato')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label d-block">Flag</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_cliente" value="cliente" {{ is_array(old('flag')) && in_array('cliente', old('flag')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="flag_cliente">Cliente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_fornecedor" value="fornecedor" {{ is_array(old('flag')) && in_array('fornecedor', old('flag')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="flag_fornecedor">Fornecedor</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_revendedor" value="revendedor" {{ is_array(old('flag')) && in_array('revendedor', old('flag')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="flag_revendedor">Revendedor</label>
                            </div>
                            @error('flag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="user-credentials" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label">Usuário</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario') }}">
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('senha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="formFornecedor">Salvar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Animação de slide para o modal */
.modal.fade .modal-dialog.modal-end {
    transform: translate(100%, 0);
    transition: transform .3s ease-out;
}

.modal.show .modal-dialog.modal-end {
    transform: translate(0, 0);
}

/* Ajuste para o modal ocupar toda a altura */
.modal-content.min-vh-100 {
    border-radius: 0;
}

/* Ajustes para mobile */
@media (max-width: 768px) {
    .modal-dialog.modal-end {
        margin: 0;
        max-width: 100%;
        width: 100%;
        height: 100%;
    }

    .modal-content.min-vh-100 {
        min-height: 100vh !important;
    }

    .modal-body {
        padding: 1rem;
    }

    .row {
        margin-right: -0.5rem;
        margin-left: -0.5rem;
    }

    .col-md-2, .col-md-3, .col-md-4, .col-md-6, .col-12 {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Função para atualizar campos baseado no tipo de pessoa
    function updateTipoPessoa() {
        var tipoPessoa = $('#tipo_pessoa').val();
        if (tipoPessoa === 'F') {
            $('#razao_social').closest('.col-md-4').hide();
            $('#nome').closest('.col-md-4').show();
        } else {
            $('#razao_social').closest('.col-md-4').show();
            $('#nome').closest('.col-md-4').hide();
        }
    }

    // Atualiza campos quando o tipo de pessoa muda
    $('#tipo_pessoa').on('change', updateTipoPessoa);

    // Atualiza campos na inicialização e quando o modal abre
    $('#modalFornecedor').on('show.bs.modal', function() {
        updateTipoPessoa();
        $('#flag_fornecedor').prop('checked', true);
        updateUserCredentials();
    });

    // Função para atualizar visibilidade dos campos de usuário/senha
    function updateUserCredentials() {
        var showCredentials = $('#flag_cliente').is(':checked') || $('#flag_revendedor').is(':checked');
        $('.user-credentials').toggle(showCredentials);
    }

    // Atualiza campos quando as flags mudam
    $('#flag_cliente, #flag_revendedor').on('change', updateUserCredentials);

    // Máscaras para os campos
    $('#documento').on('input', function() {
        var tipoPessoa = $('#tipo_pessoa').val();
        var valor = $(this).val().replace(/\D/g, '');
        
        if (tipoPessoa === 'F') {
            if (valor.length <= 11) {
                valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            }
        } else {
            if (valor.length <= 14) {
                valor = valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            }
        }
        
        $(this).val(valor);
    });

    $('#phone, #whatsapp').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');

    // Toggle de visibilidade da senha
    $('#togglePassword').on('click', function() {
        var senhaInput = $('#senha');
        var tipo = senhaInput.attr('type');
        
        senhaInput.attr('type', tipo === 'password' ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Busca CEP
    $('#cep').on('blur', function() {
        var cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            // Adiciona spinner e desabilita campos
            $(this).attr('disabled', true);
            $('#rua, #bairro, #cidade, #uf').attr('disabled', true);
            $(this).after('<div class="spinner-border spinner-border-sm ms-2" role="status"><span class="visually-hidden">Buscando CEP...</span></div>');

            // Faz a requisição
            $.getJSON(`https://viacep.com.br/ws/${cep}/json/`)
                .done(function(data) {
                    if (!data.erro) {
                        $('#rua').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#uf').val(data.uf);
                        $('#numero').focus();
                    } else {
                        toastr.error('CEP não encontrado');
                    }
                })
                .fail(function() {
                    toastr.error('Erro ao buscar CEP. Tente novamente.');
                })
                .always(function() {
                    // Remove spinner e reabilita campos
                    $('#cep').attr('disabled', false);
                    $('#rua, #bairro, #cidade, #uf').attr('disabled', false);
                    $('#cep').next('.spinner-border').remove();
                });
        }
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

    // Salvar Fornecedor via AJAX
    $('#formFornecedor').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitButton = $('#btnSalvarFornecedor');
        
        // Remove mensagens de erro anteriores
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        
        // Desabilita o botão e mostra loading
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
        
        var formData = new FormData(form[0]);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Adiciona o novo fornecedor ao select
                    var displayName = response.supplier.nome || response.supplier.razao_social;
                    var newOption = new Option(displayName, response.supplier.id, true, true);
                    $('#supplier_id').append(newOption).trigger('change');
                    
                    // Limpa o formulário e fecha o modal
                    form[0].reset();
                    $('#modalFornecedor').modal('hide');
                    
                    toastr.success('Fornecedor cadastrado com sucesso!');
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
                toastr.error('Erro ao cadastrar fornecedor');
            },
            complete: function() {
                // Reabilita o botão e remove loading
                submitButton.prop('disabled', false).html('Salvar');
            }
        });
    });

    // Inicialização das máscaras monetárias e percentuais
    $(document).ready(function() {
        // Aplica máscara monetária aos campos de preço
        $('#consumer_price, #distributor_price, #sale_price, #last_purchase_price, #unit_cost, #freight_cost').each(function() {
            $(this).maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: true,
                precision: 2
            });
        });

        // Se houver valores pré-preenchidos, formata-os (campos monetários)
        $('#consumer_price, #distributor_price, #sale_price, #last_purchase_price, #unit_cost, #freight_cost').each(function() {
            var value = $(this).val();
            if (value) {
                $(this).maskMoney('mask', value);
            }
        });

        // Aplica máscara de porcentagem
        $('#tax_percentage, #consumer_markup, #distributor_markup').each(function() {
            $(this).maskMoney({
                suffix: ' %',
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: true,
                precision: 2
            });
        });

        // Se houver valores pré-preenchidos, formata-os (campos percentuais)
        $('#tax_percentage, #consumer_markup, #distributor_markup').each(function() {
            var value = $(this).val();
            if (value) {
                $(this).maskMoney('mask', value);
            }
        });

        // Atualiza preços quando os markups mudam
        $('#consumer_markup, #distributor_markup').on('change', function() {
            var value = $(this).val().replace(/[^0-9,]/g, '').replace(',', '.');
            if (!isNaN(value)) {
                // Aqui você pode adicionar a lógica para calcular os preços baseados no markup
                console.log('Markup alterado:', value);
            }
        });
    });

    // Limpa erros ao abrir os modais
    $('.modal').on('show.bs.modal', function() {
        var form = $(this).find('form');
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
    });
});
</script>
@endpush

@endsection
