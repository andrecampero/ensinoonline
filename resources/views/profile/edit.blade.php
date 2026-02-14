@extends('layouts.app')

@section('content')
   <div class="row justify-content-center">
      <div class="col-md-8">
         <h3 class="fw-bold mb-4"><i class="fas fa-user-edit me-2 text-primary"></i> Atualizar Dados Cadastrais</h3>

         <div class="card shadow-sm border-0">
            <div class="card-body p-4">
               <form method="POST" action="{{ route('profile.update') }}">
                  @csrf

                  <div class="mb-3">
                     <label for="name" class="form-label fw-semibold">Nome *</label>
                     <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                  </div>

                  <div class="mb-3">
                     <label for="email" class="form-label fw-semibold">Email *</label>
                     <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required>
                  </div>

                  <hr class="my-4">
                  <h5 class="fw-bold mb-3">Alterar Senha</h5>

                  <div class="mb-3">
                     <label for="current_password" class="form-label fw-semibold">Senha Atual *</label>
                     <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                        name="current_password" required>
                     @error('current_password')
                        <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                  </div>

                  <div class="mb-3">
                     <label for="password" class="form-label fw-semibold">Nova Senha *</label>
                     <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        required autocomplete="new-password">
                     @error('password')
                        <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                  </div>

                  <div class="mb-4">
                     <label for="password_confirmation" class="form-label fw-semibold">Confirmar Nova Senha *</label>
                     <input type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                  </div>

                  <div class="d-grid gap-2">
                     <button type="submit" class="btn btn-primary rounded-3 py-2">
                        Salvar Alterações
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
@endsection