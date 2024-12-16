<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Rules\SupplierValidation;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = new SupplierValidation();
        $validated = $request->validate($validator->rules(), $validator->messages());

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor cadastrado com sucesso.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = new SupplierValidation();
        $validated = $request->validate($validator->rules(), $validator->messages());

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor atualizado com sucesso.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor excluído com sucesso.');
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->status = !$supplier->status;
        $supplier->save();

        return redirect()->route('suppliers.index')
            ->with('success', 'Status do fornecedor alterado com sucesso.');
    }
}
