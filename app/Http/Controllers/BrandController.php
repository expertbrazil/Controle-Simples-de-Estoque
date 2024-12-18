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
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:1000',
            'status' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['status'] = $request->has('status');
            
            Brand::create($data);
            return redirect()->route('brands.index')->with('success', 'Marca cadastrada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar marca: ' . $e->getMessage());
            return back()->with('error', 'Erro ao cadastrar marca. Por favor, tente novamente.');
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
}
