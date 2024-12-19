@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Cliente/Fornecedores/Revendedores</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Cliente/Fornecedores/Revendedores</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lista de Cliente/Fornecedores/Revendedores</span>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Adicionar Novo</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Documento</th>
                            <th>Telefone</th>
                            <th>WhatsApp</th>
                            <th>E-mail</th>
                            <th>Flag</th>
                            <th>Cidade/UF</th>
                            <th>Status</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->nome }}</td>
                                <td>{{ $supplier->documento }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->whatsapp }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>
                                    @if($supplier->flag)
                                        @foreach($supplier->flag as $flag)
                                            <span class="badge bg-secondary">{{ ucfirst($flag) }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $supplier->cidade }}/{{ $supplier->uf }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" type="checkbox" 
                                            data-id="{{ $supplier->id }}" 
                                            {{ $supplier->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" 
                                           class="btn btn-primary d-flex align-items-center justify-content-center"
                                           style="width: 32px; height: 32px;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="delete-form m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Nenhum fornecedor cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle de Status
        $('.toggle-status').on('change', function() {
            const checkbox = $(this);
            const supplierId = checkbox.data('id');
            
            $.ajax({
                url: `/suppliers/${supplierId}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                        checkbox.prop('checked', !checkbox.prop('checked')); // Reverte o estado
                    }
                },
                error: function() {
                    toastr.error('Erro ao atualizar o status');
                    checkbox.prop('checked', !checkbox.prop('checked')); // Reverte o estado
                }
            });
        });

        // Confirma exclusão
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            
            if (confirm('Tem certeza que deseja excluir este fornecedor?')) {
                form.off('submit').submit();
            }
        });
    });
</script>
@endpush
@endsection
