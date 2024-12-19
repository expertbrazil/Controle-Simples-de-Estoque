<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Rules\SupplierValidation;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(10);
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

        try {
            $data = $validated;
            
            // Remove formatação do documento
            $data['documento'] = preg_replace('/[^0-9]/', '', $data['documento']);
            
            // Remove formatação do telefone e whatsapp
            if (isset($data['phone'])) {
                $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
            }
            if (isset($data['whatsapp'])) {
                $data['whatsapp'] = preg_replace('/[^0-9]/', '', $data['whatsapp']);
            }
            
            // Remove formatação do CEP
            if (isset($data['cep'])) {
                $data['cep'] = preg_replace('/[^0-9]/', '', $data['cep']);
            }
            
            // Garante que status seja um booleano
            $data['status'] = $request->has('status') && $request->status == '1';

            // Trata o array de flags
            if (!isset($data['flag'])) {
                $data['flag'] = ['fornecedor'];
            }

            // Se for cliente ou revendedor, valida usuário e senha
            if (in_array('cliente', $data['flag']) || in_array('revendedor', $data['flag'])) {
                if (empty($data['usuario']) || empty($data['senha'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'usuario' => ['O usuário é obrigatório para clientes e revendedores'],
                            'senha' => ['A senha é obrigatória para clientes e revendedores']
                        ]
                    ], 422);
                }
            }

            $supplier = Supplier::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Fornecedor cadastrado com sucesso.',
                'supplier' => $supplier
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao cadastrar fornecedor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar fornecedor: ' . $e->getMessage()
            ], 500);
        }
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
        $validated = $request->validate($validator->rules($supplier->id), $validator->messages());

        try {
            $data = $validated;
            
            // Remove formatação do documento
            $data['documento'] = preg_replace('/[^0-9]/', '', $data['documento']);
            
            // Remove formatação do telefone e whatsapp
            if (isset($data['phone'])) {
                $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
            }
            if (isset($data['whatsapp'])) {
                $data['whatsapp'] = preg_replace('/[^0-9]/', '', $data['whatsapp']);
            }
            
            // Remove formatação do CEP
            if (isset($data['cep'])) {
                $data['cep'] = preg_replace('/[^0-9]/', '', $data['cep']);
            }
            
            // Garante que status seja um booleano
            $data['status'] = $request->has('status') && $request->status == '1';

            // Trata o array de flags
            if (!isset($data['flag'])) {
                $data['flag'] = ['fornecedor'];
            }

            // Se for cliente ou revendedor, valida usuário e senha
            if (in_array('cliente', $data['flag']) || in_array('revendedor', $data['flag'])) {
                if (empty($data['usuario']) || empty($data['senha'])) {
                    return back()->withErrors([
                        'usuario' => 'O usuário é obrigatório para clientes e revendedores',
                        'senha' => 'A senha é obrigatória para clientes e revendedores'
                    ])->withInput();
                }
            }

            $supplier->update($data);
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar fornecedor: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar fornecedor. Por favor, tente novamente.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')
                ->with('success', 'Fornecedor excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Erro ao excluir fornecedor.');
        }
    }

    public function toggleStatus(Supplier $supplier)
    {
        try {
            $supplier->status = !$supplier->status;
            $supplier->save();

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'status' => $supplier->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status.'
            ], 500);
        }
    }
}
