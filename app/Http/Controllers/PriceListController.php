<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $priceLists = PriceList::all();
        return view('price_lists.index', compact('priceLists'));
    }

    public function create()
    {
        return view('price_lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'markup_percentage' => 'required|numeric|min:0',
            'type' => 'required|in:distributor,consumer',
            'is_active' => 'boolean'
        ]);

        PriceList::create($validated);

        return redirect()->route('price-lists.index')
            ->with('success', 'Lista de preços criada com sucesso.');
    }

    public function edit(PriceList $priceList)
    {
        return view('price_lists.edit', compact('priceList'));
    }

    public function update(Request $request, PriceList $priceList)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'markup_percentage' => 'required|numeric|min:0',
            'type' => 'required|in:distributor,consumer',
            'is_active' => 'boolean'
        ]);

        $priceList->update($validated);

        return redirect()->route('price-lists.index')
            ->with('success', 'Lista de preços atualizada com sucesso.');
    }

    public function destroy(PriceList $priceList)
    {
        $priceList->delete();

        return redirect()->route('price-lists.index')
            ->with('success', 'Lista de preços removida com sucesso.');
    }
}
