<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background: #f5f5f5;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmação de Pedido</h1>
        <p>Obrigado por sua compra!</p>
    </div>

    <div class="order-info">
        <h2>Pedido #{{ $sale->id }}</h2>
        <p><strong>Data:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
        @if($sale->customer)
        <p><strong>Cliente:</strong> {{ $sale->customer->name }}</p>
        <p><strong>Email:</strong> {{ $sale->customer->email }}</p>
        @endif
    </div>

    <h3>Itens do Pedido</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        @if($sale->discount > 0)
        <p>Subtotal: R$ {{ number_format($sale->total_amount + $sale->discount, 2, ',', '.') }}</p>
        <p>Desconto: R$ {{ number_format($sale->discount, 2, ',', '.') }}</p>
        @endif
        <p>Total: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Este é um email automático, por favor não responda.</p>
        <p>Em caso de dúvidas, entre em contato conosco.</p>
    </div>
</body>
</html>
