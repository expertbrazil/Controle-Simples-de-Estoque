<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'cnpj' => 'nullable|max:18|unique:suppliers',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'whatsapp' => 'nullable|max:20',
            'address' => 'nullable|max:255',
            'neighborhood' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'state' => 'nullable|size:2',
            'zip_code' => 'nullable|max:9',
            'contact_name' => 'nullable|max:255',
            'active' => 'boolean'
        ]);

        try {
            Supplier::create($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor cadastrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar fornecedor: ' . $e->getMessage());
            return back()->with('error', 'Erro ao cadastrar fornecedor. Por favor, tente novamente.');
        }
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|max:255',
            'cnpj' => 'nullable|max:18|unique:suppliers,cnpj,' . $supplier->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'whatsapp' => 'nullable|max:20',
            'address' => 'nullable|max:255',
            'neighborhood' => 'nullable|max:100',
            'city' => 'nullable|max:100',
            'state' => 'nullable|size:2',
            'zip_code' => 'nullable|max:9',
            'contact_name' => 'nullable|max:255',
            'active' => 'boolean'
        ]);

        try {
            $supplier->update($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar fornecedor: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar fornecedor. Por favor, tente novamente.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir fornecedor: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir fornecedor. Por favor, tente novamente.');
        }
    }
}
