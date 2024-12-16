@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Fornecedores</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Novo Fornecedor
        </a>
    </div>

    @if($suppliers->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Cidade/UF</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->nome }}</td>
                            <td>{{ $supplier->tipo_pessoa == 'F' ? 'Física' : 'Jurídica' }}</td>
                            <td>{{ $supplier->documento }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->cidade }}/{{ $supplier->uf }}</td>
                            <td>
                                <span class="badge {{ $supplier->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('suppliers.toggle-status', $supplier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $supplier->status ? 'warning' : 'success' }}">
                                            <i class="bi bi-{{ $supplier->status ? 'toggle-off' : 'toggle-on' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Nenhum fornecedor cadastrado.
        </div>
    @endif
</div>
@endsection
