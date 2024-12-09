@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Configurações do Sistema</h4>
                </div>

                <form action="{{ route('parameters.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <!-- Configurações SMTP -->
                        <h5 class="mb-3">Configurações de Email (SMTP)</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Servidor SMTP</label>
                                <input type="text" name="smtp[host]" class="form-control" value="{{ $smtpConfig['host'] }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Porta</label>
                                <input type="number" name="smtp[port]" class="form-control" value="{{ $smtpConfig['port'] }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Criptografia</label>
                                <select name="smtp[encryption]" class="form-select" required>
                                    <option value="tls" {{ $smtpConfig['encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ $smtpConfig['encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email/Usuário SMTP</label>
                                <input type="email" name="smtp[username]" class="form-control" value="{{ $smtpConfig['username'] }}" required>
                                <div class="form-text">Este email também será usado como remetente</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Senha SMTP</label>
                                <div class="input-group">
                                    <input type="password" name="smtp[password]" id="smtp_password" class="form-control" value="{{ $smtpConfig['password'] }}" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#testEmailModal">
                            <i class="bi bi-envelope"></i> Testar Email
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Teste de Email -->
<div class="modal fade" id="testEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('test-email') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Teste de Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email do Destinatário</label>
                        <input type="email" name="test_email" class="form-control" required 
                               placeholder="Digite o email para teste">
                        <div class="form-text">
                            Um email de teste será enviado para este endereço.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope"></i> Enviar Email de Teste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function(e) {
            e.preventDefault();
            const passwordInput = $('#smtp_password');
            const eyeIcon = $('#eyeIcon');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                eyeIcon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
            } else {
                passwordInput.attr('type', 'password');
                eyeIcon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
            }
        });
    });
</script>
@endpush
