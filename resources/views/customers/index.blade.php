@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-people"></i> Clientes
        </h5>
        <a href="{{ route('customers.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg"></i> Novo Cliente
        </a>
    </div>
    <div class="card-body">
        @if($customers->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Nenhum cliente cadastrado.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th width="100">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    @if($customer->active)
                                        <span class="badge bg-success">Ativo</span>
                                    @else
                                        <span class="badge bg-danger">Inativo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                                                    title="Excluir">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
