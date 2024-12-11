@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Entradas de Produtos</h1>
        <a href="{{ route('product-entries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nova Entrada
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Produto</th>
                            <th class="text-end">Preço de Compra</th>
                            <th class="text-end">Quantidade</th>
                            <th>Registrado por</th>
                            <th>Observações</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $entry->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $entry->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $entry->product->name }}</div>
                                    <small class="text-muted">SKU: {{ $entry->product->sku }}</small>
                                </td>
                                <td class="text-end">R$ {{ number_format($entry->purchase_price, 2, ',', '.') }}</td>
                                <td class="text-end">{{ $entry->quantity }}</td>
                                <td>{{ $entry->user->name }}</td>
                                <td>
                                    @if($entry->notes)
                                        <small>{{ Str::limit($entry->notes, 50) }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('product-entries.show', $entry) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Ver detalhes">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('product-entries.edit', $entry) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar observações">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('product-entries.destroy', $entry) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta entrada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Excluir entrada">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox"></i>
                                    Nenhuma entrada registrada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $entries->links() }}
    </div>
</div>
@endsection
