@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Análise de Preços</h1>
        <a href="{{ route('price-histories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para Histórico
        </a>
    </div>

    <div class="row">
        <!-- Variações Significativas -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Variações Significativas Recentes</h6>
                    <span class="badge bg-info">Último mês</span>
                </div>
                <div class="card-body">
                    @if($significantChanges->isEmpty())
                        <div class="alert alert-info">
                            Nenhuma variação significativa encontrada no último mês.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Preço Anterior</th>
                                        <th>Preço Atual</th>
                                        <th>Variação</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($significantChanges as $change)
                                        <tr>
                                            <td>
                                                <a href="{{ route('price-histories.show', $change->product_id) }}">
                                                    {{ $change->product->name }}
                                                </a>
                                            </td>
                                            <td>{{ $change->formatted_old_price }}</td>
                                            <td>{{ $change->formatted_consumer_price }}</td>
                                            <td class="{{ $change->price_increase > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($change->price_increase, 2) }}%
                                            </td>
                                            <td>{{ $change->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Maiores Aumentos -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Maiores Aumentos no Último Mês</h6>
                    <span class="badge bg-danger">Top 10</span>
                </div>
                <div class="card-body">
                    @if($topPriceIncreases->isEmpty())
                        <div class="alert alert-info">
                            Nenhum aumento significativo encontrado no último mês.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Preço Anterior</th>
                                        <th>Preço Atual</th>
                                        <th>Aumento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPriceIncreases as $increase)
                                        <tr>
                                            <td>{{ $increase['name'] }}</td>
                                            <td>R$ {{ number_format($increase['old_price'], 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($increase['new_price'], 2, ',', '.') }}</td>
                                            <td class="text-danger">
                                                {{ number_format($increase['price_increase'], 2) }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
