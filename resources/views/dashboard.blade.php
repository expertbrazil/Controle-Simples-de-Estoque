@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <h4>Bem-vindo ao Sistema de Controle de Estoque</h4>
                        <p>Use o menu lateral para navegar entre as funcionalidades do sistema.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
