<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(10);
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
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
            
            $brand = Brand::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marca cadastrada com sucesso.',
                    'brand' => $brand
                ]);
            }

            return redirect()->route('brands.index')
                ->with('success', 'Marca cadastrada com sucesso.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao cadastrar marca.'
                ], 500);
            }

            return redirect()->route('brands.index')
                ->with('error', 'Erro ao cadastrar marca.');
        }
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'status' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['status'] = $request->has('status');
            
            $brand->update($data);
            return redirect()->route('brands.index')->with('success', 'Marca atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar marca: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar marca. Por favor, tente novamente.');
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return redirect()->route('brands.index')->with('success', 'Marca excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir marca: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir marca. Por favor, tente novamente.');
        }
    }

    public function toggleStatus(Brand $brand)
    {
        try {
            $brand->status = !$brand->status;
            $brand->save();

            return response()->json([
                'success' => true,
                'message' => 'Status da marca atualizado com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar status da marca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status da marca'
            ], 500);
        }
    }
}
