@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-cart"></i> Vendas
        </h5>
        <a href="{{ route('pdv.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg"></i> Nova Venda
        </a>
    </div>
    <div class="card-body">
        @if($sales->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Nenhuma venda realizada.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="100">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $sale->customer->name }}</td>
                                <td>R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($sale->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
