@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalhes da Venda #{{ $sale->id }}</h5>
            <div>
                <button class="btn btn-light btn-sm me-2" onclick="printSale()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                @if($sale->customer && $sale->customer->email)
                    <button class="btn btn-light btn-sm me-2" onclick="sendEmail()">
                        <i class="bi bi-envelope"></i> Enviar por Email
                    </button>
                @endif
                @if($sale->customer && $sale->customer->phone)
                    <button class="btn btn-light btn-sm" onclick="sendWhatsApp()">
                        <i class="bi bi-whatsapp"></i> Enviar WhatsApp
                    </button>
                @endif
            </div>
        </div>
        
        <div class="card-body">
            <!-- Informações da Venda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Informações da Venda</h6>
                    <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Vendedor:</strong> {{ $sale->user->name ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($sale->status) }}</span></p>
                    <p><strong>Forma de Pagamento:</strong> {{ ucfirst($sale->payment_method) }}</p>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Informações do Cliente</h6>
                    @if($sale->customer)
                        <p><strong>Nome:</strong> {{ $sale->customer->name }}</p>
                        <p><strong>CPF:</strong> {{ $sale->customer->cpf ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $sale->customer->email ?? 'N/A' }}</p>
                        <p><strong>Telefone:</strong> {{ $sale->customer->phone ?? 'N/A' }}</p>
                    @else
                        <p class="text-muted">Venda sem cliente registrado</p>
                    @endif
                </div>
            </div>

            <!-- Itens da Venda -->
            <h6 class="text-muted mb-3">Itens do Pedido</h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                <td class="text-end">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end">R$ {{ number_format($sale->subtotal_amount, 2, ',', '.') }}</td>
                        </tr>
                        @if($sale->discount_percent > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Desconto ({{ $sale->discount_percent }}%):</strong></td>
                                <td class="text-end">- R$ {{ number_format($sale->discount_amount, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printSale() {
    window.open('{{ route("pdv.print", $sale->id) }}', '_blank', 'width=800,height=600');
}

function sendEmail() {
    // Mostrar loading
    Swal.fire({
        title: 'Enviando email...',
        text: 'Por favor, aguarde',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fazer requisição para enviar email
    $.ajax({
        url: '{{ route("pdv.send-email", $sale->id) }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Email enviado com sucesso!'
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Erro ao enviar email. Tente novamente.'
            });
        }
    });
}

function sendWhatsApp() {
    const phone = '{{ $sale->customer->phone ?? "" }}'.replace(/\D/g, '');
    if (!phone) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Telefone do cliente não cadastrado'
        });
        return;
    }

    // Criar mensagem
    let message = `*Resumo do Pedido #{{ $sale->id }}*\n\n`;
    message += `Data: {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}\n`;
    message += `\nItens:\n`;
    @foreach($sale->items as $item)
        message += `- {{ $item->product->name }}: {{ $item->quantity }}x R$ {{ number_format($item->price, 2, ',', '.') }}\n`;
    @endforeach
    message += `\nTotal: R$ {{ number_format($sale->total, 2, ',', '.') }}`;

    // Abrir WhatsApp
    const whatsappUrl = `https://wa.me/55${phone}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .navbar {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .card-header {
        background-color: #fff !important;
        color: #000 !important;
    }
}
</style>
@endpush
