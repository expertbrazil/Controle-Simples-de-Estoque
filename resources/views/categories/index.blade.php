@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Categorias</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Lista de Categorias</span>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nova Categoria
            </a>
        </div>

        <div class="card-body">
            <div id="alertContainer"></div>
            
            @if($categories->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Nenhuma categoria cadastrada.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
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

{{-- Modal para adicionar subcategoria --}}
<div class="modal fade" id="subcategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Subcategoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subcategory_name" class="form-label">Nome da Subcategoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subcategory_name" 
                               name="name" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" class="form-check-input" 
                                   id="subcategory_active" name="active" value="1" checked>
                            <label class="form-check-label" for="subcategory_active">Categoria Ativa</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle de Status
    $('.toggle-status').on('change', function() {
        const checkbox = $(this);
        const categoryId = checkbox.data('id');
        const route = checkbox.data('route');

        $.ajax({
            url: route,
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

    // Configuração do modal de subcategoria
    const subcategoryModal = document.getElementById('subcategoryModal');
    if (subcategoryModal) {
        subcategoryModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.getAttribute('data-category-name');
            
            document.getElementById('parent_id').value = categoryId;
            document.querySelector('.modal-title').textContent = `Nova Subcategoria de ${categoryName}`;
        });
    }

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

    // Interceptar apenas os formulários de exclusão
    document.querySelectorAll('form.delete-form').forEach(form => {
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
