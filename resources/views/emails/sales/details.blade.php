@component('mail::message')
# Detalhes da Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}

@if($additionalMessage)
{{ $additionalMessage }}

---
@endif

**Data:** {{ $sale->created_at->format('d/m/Y H:i') }}  
**Status:** {{ $sale->status }}  
**Total:** R$ {{ number_format($sale->total_amount, 2, ',', '.') }}

## Itens da Venda

@component('mail::table')
| Produto | Quantidade | Preço Unit. | Subtotal |
|:--------|:----------:|------------:|---------:|
@foreach($sale->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | R$ {{ number_format($item->price, 2, ',', '.') }} | R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }} |
@endforeach
| | | **Total:** | **R$ {{ number_format($sale->total_amount, 2, ',', '.') }}** |
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
