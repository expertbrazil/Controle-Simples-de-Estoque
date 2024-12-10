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
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'cpf' => 'nullable|string|max:14',
                'cep' => 'nullable|string|max:9',
                'endereco' => 'nullable|string|max:255',
                'numero' => 'nullable|string|max:20',
                'bairro' => 'nullable|string|max:100',
                'cidade' => 'nullable|string|max:100',
                'uf' => 'nullable|string|size:2'
            ]);

            // Garante que o campo active seja true por padrão
            $validatedData['active'] = true;

            $customer = Customer::create($validatedData);

            if ($request->wantsJson()) {
                return response()->json($customer, 201);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao cadastrar cliente: ' . $e->getMessage()
                ], 422);
            }

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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'cep' => 'nullable|string|max:9',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'bairro' => 'nullable|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'uf' => 'nullable|string|size:2'
        ]);

        $validated['active'] = $request->has('active');

        try {
            $customer->update($validated);

            if ($request->wantsJson()) {
                return response()->json($customer);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao atualizar cliente: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()
                ->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $customers = Customer::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('cpf', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get();
            
        return response()->json($customers);
    }
}
