@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="bi bi-box"></i> Produtos
                    </h5>
                </div>
                <div class="col text-end">
                    <a href="{{ route('products.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle"></i> Novo Produto
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filtros -->
            <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nome, SKU ou código" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="category" class="form-select">
                            <option value="">Categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="brand" class="form-select">
                            <option value="">Marca</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="supplier" class="form-select">
                            <option value="">Fornecedor</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="stock_status" class="form-select">
                            <option value="">Status do Estoque</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Estoque Baixo</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Sem Estoque</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Disponível</option>
                        </select>
                    </div>
                </div>
            </form>

            @if($products->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhum produto cadastrado.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">ID</th>
                                <th style="width: 80px;">Imagem</th>
                                <th>SKU</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Marca</th>
                                <th>Custo Unit.</th>
                                <th>Preço Consumidor</th>
                                <th>Preço Distribuidor</th>
                                <th class="text-center">Estoque</th>
                                <th class="text-center">Status</th>
                                <th>Última Compra</th>
                                <th class="text-end" style="width: 100px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">{{ $product->id }}</td>
                                    <td class="align-middle">
                                        @if($product->image)
                                            <img src="{{ asset('images/produtos/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-thumbnail"
                                                 style="max-height: 50px;">
                                        @else
                                            <span class="text-muted">Sem imagem</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'Sem categoria' }}</td>
                                    <td>{{ $product->brand ? $product->brand->name : 'Sem marca' }}</td>
                                    <td>R$ {{ number_format($product->unit_cost, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($product->consumer_price, 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($product->distributor_price, 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->status ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->last_purchase_date)
                                            R$ {{ number_format($product->last_purchase_price, 2, ',', '.') }}
                                            <br>
                                            <small class="text-muted">{{ $product->last_purchase_date->format('d/m/Y') }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            <a href="{{ route('products.price-history', $product) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="Histórico de Preços">
                                                <i class="bi bi-clock-history"></i>
                                            </a>

                                            <form action="{{ route('products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.table tbody > tr > td {
    padding: 0.75rem;
}
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    line-height: 1;
}
.btn-sm i {
    font-size: 1rem;
    line-height: 1;
    display: block;
}
.btn-outline-primary:hover i,
.btn-outline-info:hover i,
.btn-outline-danger:hover i {
    color: white;
}
.tooltip {
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os dropdowns do Bootstrap
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
@endpush

@endsection
