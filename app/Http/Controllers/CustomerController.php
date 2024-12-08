<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:20'
        ]);

        $validated['active'] = $request->has('active');

        try {
            Customer::create($validated);
            return redirect()->route('customers.index')->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao cadastrar cliente: ' . $e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:20'
        ]);

        $validated['active'] = $request->has('active');

        try {
            $customer->update($validated);
            return redirect()->route('customers.index')->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        
        $customers = Customer::where('name', 'LIKE', "%{$term}%")
                            ->orWhere('phone', 'LIKE', "%{$term}%")
                            ->select('id', 'name', 'phone')
                            ->limit(10)
                            ->get()
                            ->map(function($customer) {
                                return [
                                    'id' => $customer->id,
                                    'label' => "{$customer->name} - {$customer->phone}",
                                    'value' => $customer->name
                                ];
                            });

        return response()->json($customers);
    }
}
