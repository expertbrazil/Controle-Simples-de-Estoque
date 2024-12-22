@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Listas de Preços</h4>
                    <a href="{{ route('price-lists.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nova Lista
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Tipo</th>
                                    <th>Markup (%)</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($priceLists as $list)
                                    <tr>
                                        <td>{{ $list->name }}</td>
                                        <td>{{ $list->type === 'distributor' ? 'Distribuidor' : 'Consumidor' }}</td>
                                        <td>{{ number_format($list->markup_percentage, 2, ',', '.') }}%</td>
                                        <td>
                                            <span class="badge bg-{{ $list->is_active ? 'success' : 'danger' }}">
                                                {{ $list->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('price-lists.edit', $list) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('price-lists.destroy', $list) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta lista?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
