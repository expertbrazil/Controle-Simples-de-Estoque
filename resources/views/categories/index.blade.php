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
            <div id="alertContainer"></div>
            
            @if($categories->isEmpty())
                <x-alert type="info" :message="'Nenhuma categoria cadastrada.'" />
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function showAlert(message, type = 'success') {
        const alertContainer = document.getElementById('alertContainer');
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertContainer.innerHTML = alertHtml;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    // Interceptar o submit do formulário de exclusão
    document.querySelectorAll('form[action^="{{ route('categories.index') }}"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!confirm('Tem certeza que deseja excluir esta categoria?')) {
                return;
            }

            const categoryId = form.action.split('/').pop();
            
            fetch(form.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover a linha da tabela
                    const row = form.closest('tr');
                    row.remove();
                    
                    // Mostrar mensagem de sucesso
                    showAlert(data.message, 'success');
                    
                    // Se não houver mais categorias, mostrar mensagem
                    const tbody = document.querySelector('tbody');
                    if (!tbody.hasChildNodes()) {
                        location.reload();
                    }
                } else {
                    // Mostrar mensagem de erro
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Erro ao excluir categoria. Por favor, tente novamente.', 'danger');
            });
        });
    });
});
</script>
@endpush
