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

        $data = $validated;
        
        // Remove formatação do documento
        $data['documento'] = preg_replace('/[^0-9]/', '', $data['documento']);
        
        // Se não houver flag selecionada, define como array vazio
        if (!isset($data['flag'])) {
            $data['flag'] = [];
        }

        // Converte o array de flags para JSON
        $data['flag'] = json_encode($data['flag']);

        $supplier = Supplier::create($data);

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
        $data = $request->all();
        
        // Remove formatação do documento
        $data['documento'] = preg_replace('/[^0-9]/', '', $data['documento']);
        
        // Se não houver flag selecionada, define como array vazio
        if (!isset($data['flag'])) {
            $data['flag'] = [];
        }

        // Converte o array de flags para JSON
        $data['flag'] = json_encode($data['flag']);

        // Verifica se as flags cliente ou revendedor foram removidas
        $oldFlags = json_decode($supplier->flag, true) ?: [];
        $newFlags = json_decode($data['flag'], true) ?: [];
        
        $hadAccess = in_array('cliente', $oldFlags) || in_array('revendedor', $oldFlags);
        $hasAccess = in_array('cliente', $newFlags) || in_array('revendedor', $newFlags);
        
        if ($hadAccess && !$hasAccess) {
            // Se tinha acesso e agora não tem mais, limpa os campos
            $data['usuario'] = null;
            $data['senha'] = null;
        } elseif (!$hasAccess) {
            // Se não tem acesso, remove os campos do request
            unset($data['usuario']);
            unset($data['senha']);
        } elseif (empty($data['senha'])) {
            // Se tem acesso mas a senha está vazia, mantém a senha atual
            unset($data['senha']);
        }

        // Atualiza o fornecedor
        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor atualizado com sucesso.');
    }

    public function destroy(Supplier $supplier)
    {
        try {
            \Log::info('Iniciando exclusão do fornecedor', [
                'id' => $supplier->id,
                'nome' => $supplier->nome_completo ?? $supplier->nome,
                'deleted_at' => $supplier->deleted_at
            ]);

            // Verifica se o fornecedor existe
            if (!$supplier->exists) {
                throw new \Exception('Fornecedor não encontrado.');
            }

            // Tenta excluir o fornecedor
            $result = $supplier->forceDelete();

            \Log::info('Resultado da exclusão', [
                'success' => $result,
                'exists' => Supplier::withTrashed()->find($supplier->id) ? 'sim' : 'não'
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', 'Fornecedor excluído com sucesso.');
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir fornecedor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('suppliers.index')
                ->with('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->status = !$supplier->status;
        $supplier->save();

        return redirect()->route('suppliers.index')
            ->with('success', 'Status do fornecedor alterado com sucesso.');
    }
}
