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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'status' => 'boolean'
            ]);

            $data = [
                'name' => $validated['name'],
                'parent_id' => $validated['parent_id'] ?? null,
                'status' => $request->has('status')
            ];

            $category = Category::create($data);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'category' => $category
                ]);
            }

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoria criada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar categoria: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar categoria. Por favor, tente novamente.'
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar categoria. Por favor, tente novamente.');
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
            // Verificar se a categoria tem produtos associados
            if ($category->products()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Não é possível excluir esta categoria pois existem produtos vinculados a ela.'
                ], 422);
            }

            // Verificar se a categoria tem subcategorias
            if ($category->children()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Não é possível excluir esta categoria pois existem subcategorias vinculadas a ela.'
                ], 422);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ Categoria excluída com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao excluir categoria: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '❌ Erro ao excluir categoria. Por favor, tente novamente.'
            ], 500);
        }
    }
}
