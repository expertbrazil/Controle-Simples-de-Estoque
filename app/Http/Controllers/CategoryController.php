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
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:categories,id',
                'active' => 'boolean'
            ]);

            $category = Category::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'parent_id' => $validated['parent_id'] ?? null,
                'active' => $validated['active'] ?? true
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoria criada com sucesso!',
                    'category' => $category
                ]);
            }

            return redirect()->route('categories.index')
                ->with('success', 'Categoria criada com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar categoria: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar categoria: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Erro ao criar categoria: ' . $e->getMessage()]);
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
                'message' => 'Erro ao buscar categorias'
            ], 500);
        }
    }
}
