@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Histórico de Preços - {{ $product->name }}</h4>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
                <div class="card-body">
                    <!-- Informações do Produto -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <strong>SKU:</strong> {{ $product->sku }}
                        </div>
                        <div class="col-md-3">
                            <strong>Código de Barras:</strong> {{ $product->barcode }}
                        </div>
                        <div class="col-md-3">
                            <strong>Categoria:</strong> {{ $product->category->name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Marca:</strong> {{ $product->brand->name }}
                        </div>
                    </div>

                    <!-- Tabela de Histórico -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Preço de Compra</th>
                                    <th>Frete</th>
                                    <th>Imposto</th>
                                    <th>Custo Unitário</th>
                                    <th>Preço Consumidor</th>
                                    <th>Preço Distribuidor</th>
                                    <th>Entrada</th>
                                    <th>Usuário</th>
                                    <th>Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($priceHistory as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                        <td>R$ {{ number_format($history->purchase_price, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($history->freight_cost, 2, ',', '.') }}</td>
                                        <td>{{ number_format($history->tax_percentage, 2, ',', '.') }}%</td>
                                        <td>R$ {{ number_format($history->unit_cost, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($history->consumer_price, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($history->distributor_price, 2, ',', '.') }}</td>
                                        <td>
                                            @if($history->entry)
                                                #{{ $history->entry->id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $history->user->name }}</td>
                                        <td>{{ $history->notes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Nenhum histórico encontrado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $priceHistory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
