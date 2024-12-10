@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-cart"></i> Detalhes da Venda #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
            </h5>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#emailModal">
                    <i class="bi bi-envelope"></i> Email
                </button>
                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $sale->customer->phone ?? '') }}?text={{ urlencode($whatsappMessage) }}" 
                   class="btn btn-outline-success" target="_blank">
                    <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="mb-3">Informações da Venda</h6>
                    <div><strong>Data:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</div>
                    <div>
                        <strong>Status:</strong> 
                        <span class="badge bg-success">{{ $sale->status }}</span>
                    </div>
                    <div><strong>Total:</strong> R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Informações do Cliente</h6>
                    <div><strong>Nome:</strong> {{ $sale->customer->name ?? 'N/A' }}</div>
                    <div><strong>Telefone:</strong> {{ $sale->customer->phone ?? 'N/A' }}</div>
                </div>
            </div>

            <h6 class="mb-3">Itens da Venda</h6>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>PRODUTO</th>
                            <th class="text-center">QUANTIDADE</th>
                            <th class="text-end">PREÇO UNIT.</th>
                            <th class="text-end">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                            <td class="text-end">R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar para Lista
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Email -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Venda por Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sales.send-email', $sale) }}" method="POST" id="emailForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="{{ $sale->customer->email ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensagem Adicional (opcional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .btn-group, .modal, .text-end .btn, header, footer {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .card-header {
        background: none !important;
        border: none !important;
    }
    @page {
        margin: 0.5cm;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: form.email.value,
            message: form.message.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email enviado com sucesso!');
            bootstrap.Modal.getInstance(document.getElementById('emailModal')).hide();
        } else {
            alert(data.message || 'Erro ao enviar email');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao enviar email');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = 'Enviar';
    });
});
</script>
@endpush
