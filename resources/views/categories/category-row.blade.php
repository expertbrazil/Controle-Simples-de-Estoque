{{-- Linha da categoria --}}
<tr>
    <td style="padding-left: {{ $level * 20 }}px">
        <i class="bi bi-diagram-2 me-2"></i>{{ $category->name }}
    </td>
    <td>
        <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input toggle-status" 
                   data-id="{{ $category->id }}"
                   data-route="{{ route('categories.toggle-status', $category) }}"
                   {{ $category->status ? 'checked' : '' }}>
        </div>
    </td>
    <td>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-secondary add-subcategory" 
                    data-bs-toggle="modal" 
                    data-bs-target="#subcategoryModal"
                    data-category-id="{{ $category->id }}"
                    data-category-name="{{ $category->name }}">
                <i class="bi bi-plus-lg"></i>
            </button>
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-info">
                <i class="bi bi-pencil-square"></i>
            </a>
            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- Modal para adicionar subcategoria --}}
<div class="modal fade" id="addSubcategoryModal-{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Subcategoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $category->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name-{{ $category->id }}" class="form-label">Nome da Subcategoria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name-{{ $category->id }}" 
                               name="name" required maxlength="255">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="active" value="0">
                            <input type="checkbox" class="form-check-input" 
                                   id="active-{{ $category->id }}" name="active" value="1" checked>
                            <label class="form-check-label" for="active-{{ $category->id }}">Categoria Ativa</label>
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

@if($category->children)
    @foreach($category->children as $child)
        @include('categories.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
