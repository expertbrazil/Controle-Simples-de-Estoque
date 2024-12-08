@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-tags"></i> Categorias
            </h5>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                + Nova Categoria
            </a>
        </div>

        <div class="card-body">
            @if($categories->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhuma categoria cadastrada.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                @include('categories.category-row', ['category' => $category, 'level' => 0])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
