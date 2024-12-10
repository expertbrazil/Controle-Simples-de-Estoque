<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovante de Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .receipt {
            max-width: 80mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .items {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .items th, .items td {
            text-align: left;
            padding: 5px;
        }

        .items th {
            border-bottom: 1px solid #000;
        }

        .totals {
            margin-top: 10px;
            text-align: right;
        }

        .totals p {
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company-name">{{ config('app.name', 'Laravel') }}</div>
            <div>CNPJ: XX.XXX.XXX/0001-XX</div>
            <div>{{ date('d/m/Y H:i:s') }}</div>
        </div>

        <div class="info">
            <p><strong>Venda #{{ $sale->id }}</strong></p>
            <p>Data: {{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
            <p>Vendedor: {{ $sale->user->name }}</p>
            @if($sale->customer)
                <p>Cliente: {{ $sale->customer->name }}</p>
                @if($sale->customer->cpf)
                    <p>CPF: {{ $sale->customer->cpf }}</p>
                @endif
            @endif
            <p>Forma de Pagamento: {{ ucfirst($sale->payment_method) }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qtd</th>
                    <th>Valor</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p>Subtotal: R$ {{ number_format($sale->subtotal_amount, 2, ',', '.') }}</p>
            @if($sale->discount_percent > 0)
                <p>Desconto ({{ $sale->discount_percent }}%): 
                   R$ {{ number_format($sale->discount_amount, 2, ',', '.') }}</p>
            @endif
            <p><strong>Total: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</strong></p>
        </div>

        <div class="footer">
            <p>Obrigado pela preferência!</p>
            <p>Volte sempre!</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Fechar</button>
    </div>
</body>
</html>
