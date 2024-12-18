<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        // Buscar categorias pai (sem parent_id)
        $rootCategories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
            
        // Buscar todas as categorias para o select
        $allCategories = Category::orderBy('name')->get();

        return view('categories.index', [
            'categories' => $rootCategories,
            'allCategories' => $allCategories
        ]);
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        try {
            // Garante que status seja um booleano
            $validated['status'] = $request->has('status') && $request->status == '1';
            
            $category = Category::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria cadastrada com sucesso.',
                    'category' => $category
                ]);
            }

            return redirect()->route('categories.index')
                ->with('success', 'Categoria cadastrada com sucesso.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar categoria.'
                ], 500);
            }

            return redirect()->route('categories.index')
                ->with('error', 'Erro ao cadastrar categoria.');
        }
    }

    public function getCategories()
    {
        try {
            $categories = Category::orderBy('name')->get();
            
            // Função para adicionar nível às categorias
            $addLevel = function ($categories, $level = 0) use (&$addLevel) {
                $result = [];
                foreach ($categories as $category) {
                    $category->level = $level;
                    $result[] = $category;
                    if ($category->children->count() > 0) {
                        $result = array_merge($result, $addLevel($category->children, $level + 1));
                    }
                }
                return $result;
            };

            // Buscar categorias raiz e processar hierarquia
            $rootCategories = Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('name')
                ->get();
            
            $hierarchicalCategories = $addLevel($rootCategories);

            return response()->json([
                'success' => true,
                'data' => $hierarchicalCategories
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar categorias: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '❌ Erro ao buscar categorias. Por favor, tente novamente.'
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->children()->exists()) {
                return back()->with('error', 'Não é possível excluir uma categoria que possui subcategorias.');
            }
            
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir categoria: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir categoria. Por favor, tente novamente.');
        }
    }

    public function toggleStatus(Category $category)
    {
        try {
            $category->status = !$category->status;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Status da categoria atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status da categoria: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status da categoria'
            ], 500);
        }
    }
}
