@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Detalhes da Entrada</h1>
        <div>
            <a href="{{ route('product-entries.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Produto -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações do Produto</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Produto:</th>
                            <td>{{ $entry->product->name }}</td>
                        </tr>
                        <tr>
                            <th>SKU:</th>
                            <td>{{ $entry->product->sku }}</td>
                        </tr>
                        <tr>
                            <th>Código de Barras:</th>
                            <td>{{ $entry->product->barcode }}</td>
                        </tr>
                        <tr>
                            <th>Peso Cadastrado:</th>
                            <td>{{ number_format($entry->product->weight_kg, 3, ',', '.') }} kg</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informações da Entrada -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações da Entrada</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Data da Entrada:</th>
                            <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Registrado por:</th>
                            <td>{{ $entry->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Quantidade:</th>
                            <td>{{ $entry->quantity }}</td>
                        </tr>
                        <tr>
                            <th>Observações:</th>
                            <td>{{ $entry->notes ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Valores e Cálculos -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Valores e Cálculos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Valores Informados -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Valores Informados</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 200px;">Valor da Compra:</th>
                                    <td>R$ {{ number_format($entry->purchase_price, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Imposto (%):</th>
                                    <td>{{ number_format($entry->tax_percentage, 2, ',', '.') }}%</td>
                                </tr>
                                <tr>
                                    <th>Valor do Imposto:</th>
                                    <td>R$ {{ number_format($comparison['tax_amount'], 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Frete:</th>
                                    <td>R$ {{ number_format($entry->freight_cost, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Peso:</th>
                                    <td>{{ number_format($entry->weight_kg, 3, ',', '.') }} kg</td>
                                </tr>
                                <tr>
                                    <th>Valor do Frete:</th>
                                    <td>R$ {{ number_format($comparison['freight_amount'], 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Custos e Comparação -->
                        <div class="col-md-6">
                            <h6 class="mb-3">Custos e Comparação</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 200px;">Custo Unitário:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>R$ {{ number_format($entry->unit_cost, 2, ',', '.') }}</span>
                                        @if(abs($comparison['unit_cost_difference']) > 0.01)
                                            <span class="text-{{ $comparison['unit_cost_difference'] > 0 ? 'danger' : 'success' }}">
                                                ({{ $comparison['unit_cost_difference'] > 0 ? '+' : '' }}{{ number_format($comparison['unit_cost_difference'], 2, ',', '.') }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Custo Total:</th>
                                    <td class="d-flex justify-content-between">
                                        <span>R$ {{ number_format($entry->total_cost, 2, ',', '.') }}</span>
                                        @if(abs($comparison['total_cost_difference']) > 0.01)
                                            <span class="text-{{ $comparison['total_cost_difference'] > 0 ? 'danger' : 'success' }}">
                                                ({{ $comparison['total_cost_difference'] > 0 ? '+' : '' }}{{ number_format($comparison['total_cost_difference'], 2, ',', '.') }})
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Custo Unitário (Calculado):</th>
                                    <td>R$ {{ number_format($comparison['calculated_unit_cost'], 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Custo Total (Calculado):</th>
                                    <td>R$ {{ number_format($comparison['calculated_total_cost'], 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if(abs($comparison['unit_cost_difference']) > 0.01 || abs($comparison['total_cost_difference']) > 0.01)
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle"></i>
                            Há diferenças entre os valores informados e calculados. Verifique os cálculos.
                        </div>
                    @else
                        <div class="alert alert-success mt-3">
                            <i class="bi bi-check-circle"></i>
                            Os valores informados estão corretos e conferem com os cálculos.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
