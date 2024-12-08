{{-- Linha da categoria --}}
<tr>
    <td>
        {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}
        @if($level > 0)
            └─
        @endif
        {{ $category->name }}
    </td>
    <td>{{ $category->description }}</td>
    <td>
        @if($category->active)
            <span class="badge bg-success">Ativa</span>
        @else
            <span class="badge bg-danger">Inativa</span>
        @endif
    </td>
    <td>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addSubcategoryModal-{{ $category->id }}"
                    title="Adicionar Subcategoria">
                <i class="bi bi-plus-lg"></i>
            </button>
            <a href="{{ route('categories.edit', $category) }}" 
               class="btn btn-sm btn-primary"
               title="Editar">
                <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('categories.destroy', $category) }}" 
                  method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" 
                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')"
                        title="Excluir">
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
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name-{{ $category->id }}" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" 
                               id="name-{{ $category->id }}" 
                               name="name" required maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="description-{{ $category->id }}" class="form-label">Descrição</label>
                        <textarea class="form-control" 
                                  id="description-{{ $category->id }}" 
                                  name="description" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoria Pai (Opcional)</label>
                        <select class="form-select" name="parent_id">
                            <option value="">Selecione uma categoria pai</option>
                            @foreach($allCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" 
                                        {{ $category->id == $parentCategory->id ? 'selected' : '' }}>
                                    {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parentCategory->level ?? 0) !!}
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" 
                                   id="active-{{ $category->id }}" 
                                   name="active" checked>
                            <label class="form-check-label" for="active-{{ $category->id }}">
                                Categoria Ativa
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Renderiza as categorias filhas --}}
@if($category->children)
    @foreach($category->children as $child)
        @include('categories.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
