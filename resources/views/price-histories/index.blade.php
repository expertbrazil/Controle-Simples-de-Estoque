@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Histórico de Preços</h1>
        <div>
            <a href="{{ route('price-histories.analysis') }}" class="btn btn-primary">
                <i class="bi bi-graph-up"></i> Análise de Preços
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Produto</label>
                    <select name="product_id" class="form-select">
                        <option value="">Todos os produtos</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Data Final</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Variação Significativa</label>
                    <div class="input-group">
                        <input type="number" name="threshold" class="form-control" value="{{ request('threshold', 10) }}" min="1" max="100">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                <div class="col-md-2 align-self-end">
                    <div class="form-check">
                        <input type="checkbox" name="significant_changes" class="form-check-input" value="1" {{ request('significant_changes') ? 'checked' : '' }}>
                        <label class="form-check-label">Apenas variações significativas</label>
                    </div>
                </div>
                <div class="col-md-1 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($histories->isEmpty())
                <div class="alert alert-info">
                    Nenhum registro encontrado com os filtros selecionados.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Produto</th>
                                <th>Preço de Compra</th>
                                <th>Custo Unitário</th>
                                <th>Preço Consumidor</th>
                                <th>Preço Distribuidor</th>
                                <th>Imposto</th>
                                <th>Frete</th>
                                <th>Usuário</th>
                                <th>Variação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $history)
                                <tr>
                                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('price-histories.show', $history->product_id) }}">
                                            {{ $history->product->name }}
                                        </a>
                                    </td>
                                    <td>{{ $history->formatted_purchase_price }}</td>
                                    <td>{{ $history->formatted_unit_cost }}</td>
                                    <td>{{ $history->formatted_consumer_price }}</td>
                                    <td>{{ $history->formatted_distributor_price }}</td>
                                    <td>{{ $history->formatted_tax_percentage }}</td>
                                    <td>{{ $history->formatted_freight_cost }}</td>
                                    <td>{{ $history->user->name }}</td>
                                    <td class="{{ $history->price_increase > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($history->price_increase, 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $histories->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
