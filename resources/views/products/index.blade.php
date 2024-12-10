@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-box-seam"></i> Produtos
            </h5>
            <a href="{{ route('products.create') }}" class="btn btn-light">
                <i class="bi bi-plus-lg"></i> Novo Produto
            </a>
        </div>
        <div class="card-body">
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
                                <th>Nome</th>
                                <th>SKU</th>
                                <th>Categoria</th>
                                <th class="text-end">Preço Venda</th>
                                <th class="text-end">Preço Custo</th>
                                <th class="text-center">Estoque</th>
                                <th class="text-center">Status</th>
                                <th class="text-end" style="width: 100px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">{{ $product->id }}</td>
                                    <td class="align-middle">
                                        @if($product->image)
                                            <img src="/images/produtos/{{ $product->image }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="text-center" style="width: 50px; height: 50px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        @if($product->description)
                                            <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $product->sku ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $product->category->name ?? 'Sem categoria' }}
                                        </span>
                                    </td>
                                    <td class="text-end">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td class="text-end">R$ {{ number_format($product->cost_price, 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group float-end" role="group">
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        title="Excluir">
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
.table > :not(caption) > * > * {
    padding: 0.75rem;
}
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}
.btn-group .btn {
    border-radius: 4px !important;
    margin: 0 2px;
}
</style>
@endpush
@endsection
