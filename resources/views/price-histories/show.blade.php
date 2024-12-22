@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Histórico de Preços - {{ $product->name }}</h1>
        <a href="{{ route('price-histories.index') }}" class="btn btn-secondary">Voltar</a>
    </div>

    <!-- Gráfico de Evolução de Preços -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Evolução de Preços</h6>
        </div>
        <div class="card-body">
            <canvas id="priceChart"></canvas>
        </div>
    </div>

    <!-- Tabela de Histórico -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Histórico Detalhado</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Preço de Compra</th>
                            <th>Custo Unitário</th>
                            <th>Preço Consumidor</th>
                            <th>Preço Distribuidor</th>
                            <th>Imposto</th>
                            <th>Frete</th>
                            <th>Usuário</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $history->formatted_purchase_price }}</td>
                                <td>{{ $history->formatted_unit_cost }}</td>
                                <td>{{ $history->formatted_consumer_price }}</td>
                                <td>{{ $history->formatted_distributor_price }}</td>
                                <td>{{ $history->formatted_tax_percentage }}</td>
                                <td>{{ $history->formatted_freight_cost }}</td>
                                <td>{{ $history->user->name }}</td>
                                <td>{{ $history->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('priceChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.date),
            datasets: [
                {
                    label: 'Preço de Compra',
                    data: chartData.map(item => item.purchase_price),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Custo Unitário',
                    data: chartData.map(item => item.unit_cost),
                    borderColor: 'rgb(255, 159, 64)',
                    tension: 0.1
                },
                {
                    label: 'Preço Consumidor',
                    data: chartData.map(item => item.consumer_price),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'Preço Distribuidor',
                    data: chartData.map(item => item.distributor_price),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Evolução de Preços ao Longo do Tempo'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toFixed(2);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
