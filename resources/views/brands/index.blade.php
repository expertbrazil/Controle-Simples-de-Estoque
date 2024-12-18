@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Marcas</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Marcas</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lista de Marcas</span>
            <a href="{{ route('brands.create') }}" class="btn btn-primary">Nova Marca</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th style="width: 120px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->description }}</td>
                            <td>
                                @if($brand->status)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta marca?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhuma marca cadastrada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
