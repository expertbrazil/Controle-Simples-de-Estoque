@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Análise de Preços</h1>
        <a href="{{ route('price-histories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para Histórico
        </a>
    </div>

    <!-- Resumo em Cards -->
    <div class="row mb-4">
        <div class="col-xl-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Produtos com Redução de Preço
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $priceChangesData['decreasesCount'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-down-circle text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Produtos com Aumento de Preço
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $priceChangesData['increasesCount'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-circle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Produtos sem Alteração
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $priceChangesData['noChangeCount'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-dash-circle text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 - Maiores Aumentos</h6>
                </div>
                <div class="card-body">
                    <canvas id="increasesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 - Maiores Reduções</h6>
                </div>
                <div class="card-body">
                    <canvas id="decreasesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas -->
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
                                                <div class="d-flex align-items-center">
                                                    @if($change['product_image'])
                                                        <img src="{{ asset('images/produtos/' . $change['product_image']) }}" 
                                                             alt="{{ $change['product_name'] }}" 
                                                             class="me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="bi bi-box text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-medium">{{ $change['product_name'] }}</div>
                                                        <small class="text-muted">{{ $change['product_sku'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $change['formatted_old_price'] }}</td>
                                            <td>{{ $change['formatted_new_price'] }}</td>
                                            <td class="{{ $change['price_increase'] > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($change['price_increase'], 2) }}%
                                            </td>
                                            <td>{{ $change['created_at']->format('d/m/Y') }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">Maiores Reduções no Último Mês</h6>
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
                                            <td>{{ $increase['product_name'] }}</td>
                                            <td>{{ $increase['formatted_old_price'] }}</td>
                                            <td>{{ $increase['formatted_new_price'] }}</td>
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

@push('styles')
<style>
.badge {
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}
.border-left-success {
    border-left: .25rem solid #1cc88a!important;
}
.border-left-danger {
    border-left: .25rem solid #e74a3b!important;
}
.border-left-info {
    border-left: .25rem solid #36b9cc!important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados para o gráfico de aumentos
    const increasesData = @json($priceChangesData['increases']);
    const increasesChart = new Chart(document.getElementById('increasesChart'), {
        type: 'bar',
        data: {
            labels: increasesData.map(item => item.product),
            datasets: [{
                label: 'Aumento (%)',
                data: increasesData.map(item => item.variation),
                backgroundColor: 'rgba(231, 74, 59, 0.8)',
                borderColor: 'rgba(231, 74, 59, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Variação (%)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Dados para o gráfico de reduções
    const decreasesData = @json($priceChangesData['decreases']);
    const decreasesChart = new Chart(document.getElementById('decreasesChart'), {
        type: 'bar',
        data: {
            labels: decreasesData.map(item => item.product),
            datasets: [{
                label: 'Redução (%)',
                data: decreasesData.map(item => item.variation),
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Variação (%)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush
