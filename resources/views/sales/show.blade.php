@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-cart"></i> Detalhes da Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Informações da Venda</h6>
                <p><strong>Data:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($sale->status) }}</span></p>
                <p><strong>Total:</strong> R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</p>
            </div>
            <div class="col-md-6">
                <h6>Informações do Cliente</h6>
                <p><strong>Nome:</strong> {{ $sale->customer->name }}</p>
                <p><strong>Telefone:</strong> {{ $sale->customer->phone }}</p>
            </div>
        </div>

        <h6>Itens da Venda</h6>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th width="150">Quantidade</th>
                        <th width="200">Preço Unit.</th>
                        <th width="200">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('sales.index') }}" class="btn btn-light">Voltar para Lista</a>
        </div>
    </div>
</div>
@endsection
