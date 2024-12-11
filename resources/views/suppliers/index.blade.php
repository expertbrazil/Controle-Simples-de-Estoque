@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Fornecedores</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Fornecedores</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lista de Fornecedores</span>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Novo Fornecedor</a>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Contato</th>
                        <th>Telefone</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->cnpj }}</td>
                        <td>{{ $supplier->contact_name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>
                            @if($supplier->active)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-danger">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum fornecedor cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
