@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

        <!-- Botões de Ação -->
<div class="actions-bar mb-4 no-print">
    <button class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimir
    </button>
    <a href="{{ route('pdv.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar ao PDV
    </a>
    @if($order->customer->email)
    <button class="btn btn-info" onclick="sendEmail()">
        <i class="fas fa-envelope"></i> Enviar por E-mail
    </button>
    @endif
</div>

<!-- Recibo - Esta parte será impressa -->
<div class="receipt-container">
            <div class="receipt-container">
                <div class="receipt-header">
                    <h2>Recibo da Venda #{{ $order->id }}</h2>
                </div>
                <div class="company-info">
                    <h3>Informações da Empresa</h3>
                    <p>Nome: {{ config('app.name') }}</p>
                    <p>Endereço: {{ config('app.address') }}</p>
                </div>
                <div class="receipt-section">
                    <h4>Informações do Cliente</h4>
                    <div class="customer-info">
                        <p>
                            <strong>Nome:</strong> 
                            {{ $order->customer->tipo_pessoa === 'J' ? $order->customer->razao_social : $order->customer->nome }}
                        </p>
                        <p>
                            <strong>Documento:</strong> 
                            {{ $order->customer->documento }}
                        </p>
                    </div>
                </div>
                <div class="receipt-section">
                    <h4>Itens do Pedido</h4>
                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-right">Preço</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="text-right">R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                <td class="text-right">R$ {{ number_format($order->items->sum(function($item) { return $item->quantity * $item->price; }), 2, ',', '.') }}</td>
                            </tr>
                            @if($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-right"><strong>Desconto:</strong></td>
                                <td class="text-right">{{ $order->discount }}%</td>
                            </tr>
                            @endif
                            <tr class="total-row">
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right"><strong>R$ {{ number_format($order->total, 2, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="receipt-section">
                    <h4>Informações Adicionais</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1">
                                <strong>Forma de Pagamento:</strong> 
                                {{ $order->paymentMethod->name }}
                            </p>
                            <p class="mb-1">
                                <strong>Tipo de Preço:</strong> 
                                {{ $order->price_type === 'consumer' ? 'Consumidor' : 'Distribuidor' }}
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p class="mb-1">
                                <strong>Data:</strong> 
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mb-1">
                                <strong>Vendedor:</strong> 
                                {{ $order->user->name }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="receipt-footer">
                    <p>Recibo gerado automaticamente pelo sistema.</p>
                </div>
</div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Estilos gerais do recibo */
.receipt-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.receipt-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #eee;
}

.receipt-header h2 {
    margin-bottom: 15px;
    color: #333;
    font-size: 24px;
}

.company-info {
    margin-top: 10px;
}

.company-info h3 {
    font-size: 20px;
    margin-bottom: 5px;
}

.receipt-section {
    margin-bottom: 30px;
}

.receipt-section h4 {
    color: #555;
    margin-bottom: 15px;
    padding-bottom: 5px;
    border-bottom: 1px solid #eee;
    font-size: 18px;
}

.customer-info p {
    margin-bottom: 8px;
}

/* Estilos da tabela */
.receipt-table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

.receipt-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px;
    font-weight: 600;
}

.receipt-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.receipt-table tbody tr:hover {
    background-color: #f8f9fa;
}

.total-row {
    font-size: 1.1em;
    background-color: #f8f9fa;
}

.total-row td {
    border-top: 2px solid #dee2e6;
}

.receipt-footer {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.receipt-footer p {
    margin-bottom: 15px;
}

/* Estilos dos botões */
.actions-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

/* Estilos específicos para impressão */
@media print {
    body * {
        visibility: hidden;
    }
    
    .receipt-container,
    .receipt-container * {
        visibility: visible;
    }
    
    .receipt-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 15px;
        margin: 0;
        box-shadow: none;
    }
    
    .no-print {
        display: none !important;
    }
    
    .container {
        width: 100%;
        max-width: none;
        padding: 0;
        margin: 0;
    }
    
    .col-md-8 {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
    }
    
    .receipt-table {
        page-break-inside: avoid;
    }
    
    /* Força fundo branco e texto preto */
    * {
        background: white !important;
        color: black !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Ajustes de tamanho para impressão */
    .receipt-header h2 { font-size: 20px; }
    .company-info h3 { font-size: 16px; }
    .receipt-section h4 { font-size: 14px; }
    body { font-size: 12px; }
    .total-row { font-size: 14px; }
}
</style>
@endpush

@push('scripts')
<script>
function sendEmail() {
    // Implementar envio de email
    alert('Funcionalidade de envio de email será implementada em breve.');
}
</script>
@endpush
@endsection
