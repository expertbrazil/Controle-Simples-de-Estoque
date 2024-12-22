<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('term');
        
        $customers = Customer::where('nome', 'LIKE', "%{$search}%")
            ->orWhere('documento', 'LIKE', "%{$search}%")
            ->select('id', 'nome as name', 'documento as document')
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'value' => $customer->name,
                    'label' => $customer->name . ' - ' . $customer->document
                ];
            });

        return response()->json($customers);
    }
}
