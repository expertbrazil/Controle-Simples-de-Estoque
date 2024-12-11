@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalhes da Entrada</h5>
            <div>
                <a href="{{ route('product-entries.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Informações do Produto</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Nome:</dt>
                        <dd class="col-sm-8">{{ $productEntry->product->name }}</dd>

                        <dt class="col-sm-4">SKU:</dt>
                        <dd class="col-sm-8">{{ $productEntry->product->sku }}</dd>

                        <dt class="col-sm-4">Categoria:</dt>
                        <dd class="col-sm-8">{{ $productEntry->product->category->name }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h6>Informações da Entrada</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Data:</dt>
                        <dd class="col-sm-8">{{ $productEntry->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Registrado por:</dt>
                        <dd class="col-sm-8">{{ $productEntry->user->name }}</dd>

                        <dt class="col-sm-4">Status do Estoque:</dt>
                        <dd class="col-sm-8">
                            <span class="text-success">
                                <i class="bi bi-check-circle"></i> Atualizado
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>PREÇO DE COMPRA (por unidade)</th>
                            <th>QUANTIDADE</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R$ {{ $productEntry->purchase_price_formatted }}</td>
                            <td>{{ $productEntry->quantity }}</td>
                            <td>R$ {{ $productEntry->total_formatted }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($productEntry->notes)
            <div class="mt-4">
                <h6>Observações</h6>
                <p class="mb-0">{{ $productEntry->notes }}</p>
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('product-entries.edit', $productEntry) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar Entrada
                </a>
                <form action="{{ route('product-entries.destroy', $productEntry) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta entrada?')">
                        <i class="bi bi-trash"></i> Excluir Entrada
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
