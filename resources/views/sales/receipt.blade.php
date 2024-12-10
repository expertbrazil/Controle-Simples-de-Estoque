@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Comprovante de Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="mb-3">Cliente:</h6>
                            <div>
                                <strong>Nome:</strong> {{ $sale->customer->name }}<br>
                                <strong>Telefone:</strong> {{ $sale->customer->phone }}<br>
                                @if($sale->customer->email)
                                    <strong>Email:</strong> {{ $sale->customer->email }}<br>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="mb-3">Detalhes da Venda:</h6>
                            <div>
                                <strong>Data:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}<br>
                                <strong>Vendedor:</strong> {{ $sale->user->name }}<br>
                                <strong>Status:</strong> 
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success">Concluída</span>
                                @elseif($sale->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelada</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($sale->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td class="text-end">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                                </tr>
                                @if($sale->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end">
                                        <strong>Desconto 
                                            @if($sale->discount_type === 'percentage')
                                                ({{ $sale->discount_value }}%)
                                            @endif
                                        :</strong>
                                    </td>
                                    <td class="text-end">R$ {{ number_format($sale->discount, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>R$ {{ number_format($sale->final_amount, 2, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <h6 class="mb-3">Pagamento:</h6>
                            <div>
                                <strong>Método:</strong>
                                @switch($sale->payment_method)
                                    @case('money')
                                        Dinheiro
                                        @break
                                    @case('credit_card')
                                        Cartão de Crédito
                                        @if($sale->installments > 1)
                                            ({{ $sale->installments }}x)
                                        @endif
                                        @break
                                    @case('debit_card')
                                        Cartão de Débito
                                        @break
                                    @case('pix')
                                        PIX
                                        @break
                                    @default
                                        {{ $sale->payment_method }}
                                @endswitch
                                <br>
                                <strong>Valor Pago:</strong> R$ {{ number_format($sale->paid_amount, 2, ',', '.') }}<br>
                                @if($sale->change_amount > 0)
                                    <strong>Troco:</strong> R$ {{ number_format($sale->change_amount, 2, ',', '.') }}<br>
                                @endif
                                <strong>Status:</strong>
                                @switch($sale->payment_status)
                                    @case('completed')
                                        <span class="badge bg-success">Pago</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">Pendente</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($sale->payment_status) }}</span>
                                @endswitch
                            </div>
                        </div>
                        @if($sale->notes)
                        <div class="col-sm-6">
                            <h6 class="mb-3">Observações:</h6>
                            <p>{{ $sale->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn, nav, footer {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background: none !important;
            border: none !important;
        }
        body {
            margin: 0;
            padding: 0;
            background: white;
        }
        .container {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endpush
@endsection
