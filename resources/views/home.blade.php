@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row g-4">
        <!-- Card de Produtos -->
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total de Produtos</h6>
                            <h2 class="card-title mb-0">{{ $totalProducts ?? 0 }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-primary fs-1"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">
                        Ver todos os produtos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Categorias -->
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total de Categorias</h6>
                            <h2 class="card-title mb-0">{{ $totalCategories ?? 0 }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-tags text-success fs-1"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">
                        Ver todas as categorias <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Vendas -->
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total de Vendas</h6>
                            <h2 class="card-title mb-0">{{ $totalSales ?? 0 }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart text-info fs-1"></i>
                        </div>
                    </div>
                    <hr>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">
                        Ver todas as vendas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Valor Total -->
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Valor Total em Estoque</h6>
                            <h2 class="card-title mb-0">R$ {{ number_format($totalValue ?? 0, 2, ',', '.') }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-warning fs-1"></i>
                        </div>
                    </div>
                    <hr>
                    <span class="text-muted small">Atualizado em {{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Produtos com Estoque Baixo -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                        Produtos com Estoque Baixo
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Estoque Atual</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts ?? [] as $product)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->sku }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Editar Produto">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">
                                            <i class="bi bi-emoji-smile"></i>
                                            Nenhum produto com estoque baixo
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Vendas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-primary"></i>
                        Últimas Vendas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Valor</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales ?? [] as $sale)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $sale->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $sale->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>{{ $sale->customer_name }}</td>
                                        <td class="text-end">R$ {{ number_format($sale->total, 2, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $sale->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">
                                            <i class="bi bi-bag"></i>
                                            Nenhuma venda registrada
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
