@extends('layouts.app')

@section('content')
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="fas fa-user-tie me-2 text-primary"></i> Gerenciamento de Professores</h3>
      <div class="d-flex gap-2">
         <form action="{{ route('professores.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-start rounded-pill border-end-0 ps-3"
               placeholder="Buscar por nome ou email..." value="{{ request('search') }}"
               style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
            <button class="btn btn-outline-secondary rounded-end rounded-pill border-start-0 pe-3" type="submit"
               style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
               <i class="fas fa-search"></i>
            </button>
         </form>
         @if(auth()->user()->role === 'admin')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
               <i class="fas fa-plus me-2"></i> Novo Professor
            </button>
         @endif
      </div>
   </div>

   <div class="card shadow-sm border-0">
      <div class="card-body p-0">
         <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
               <thead class="bg-light">
                  <tr>
                     <th class="ps-4" style="width: 40%;">Nome</th>
                     <th style="width: 40%;">Email</th>
                     @if(auth()->user()->role === 'admin')
                        <th class="text-end pe-4" style="width: 20%;">Ações</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
                  @forelse($professores as $professor)
                     <tr>
                        <td class="ps-4">
                           <span class="fw-semibold text-dark">{{ $professor->name }}</span>
                        </td>
                        <td>
                           <span class="text-muted">{{ $professor->email }}</span>
                        </td>
                        @if(auth()->user()->role === 'admin')
                           <td class="text-end pe-4">
                              <button class="btn btn-sm btn-light text-primary border-0 me-2" data-bs-toggle="modal"
                                 data-bs-target="#editModal{{ $professor->id }}" title="Editar">
                                 <i class="fas fa-edit"></i>
                              </button>
                              <form action="{{ route('professores.destroy', $professor) }}" method="POST" class="d-inline">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-sm btn-light text-danger border-0"
                                    onclick="return confirm('Tem certeza que deseja excluir este professor?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                 </button>
                              </form>
                           </td>
                        @endif
                     </tr>
                  @empty
                     <tr>
                        <td colspan="3" class="text-center py-5 text-muted">
                           <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                           <br>Nenhum professor cadastrado no momento.
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>

   @foreach($professores as $professor)
      <!-- Edit Modal -->
      <div class="modal fade" id="editModal{{ $professor->id }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
               <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold">Editar Professor</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form action="{{ route('professores.update', $professor) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body py-4">
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Nome *</label>
                        <input type="text" name="nome" class="form-control rounded-3" value="{{ $professor->name }}" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ $professor->email }}"
                           required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control rounded-3"
                           value="{{ $professor->data_nascimento ? \Carbon\Carbon::parse($professor->data_nascimento)->format('Y-m-d') : '' }}">
                     </div>
                  </div>
                  <div class="modal-footer border-0 pt-0">
                     <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                     <button type="submit" class="btn btn-primary rounded-3 px-4">Salvar Alterações</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   @endforeach

   <!-- Create Modal -->
   <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
               <h5 class="modal-title fw-bold">Novo Professor</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('professores.store') }}" method="POST">
               @csrf
               <div class="modal-body py-4">
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Nome *</label>
                     <input type="text" name="nome" class="form-control rounded-3" placeholder="Ex: João da Silva"
                        required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Email *</label>
                     <input type="email" name="email" class="form-control rounded-3" placeholder="Ex: joao@email.com"
                        required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Data de Nascimento</label>
                     <input type="date" name="data_nascimento" class="form-control rounded-3">
                  </div>
               </div>
               <div class="modal-footer border-0 pt-0">
                  <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary rounded-3 px-4">Cadastrar Professor</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   </div>
@endsection

@section('scripts')
   <script>
      let timeout = null;
      window.filterTable = function (column, value) {
         clearTimeout(timeout);
         timeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (value) {
               url.searchParams.set(column, value);
            } else {
               url.searchParams.delete(column);
            }
            // Reset to page 1 on new filter
            url.searchParams.delete('page');
            window.location.href = url.toString();
         }, 500); // 500ms debounce
      }

      // Auto-focus logic: Check which filter was active and focus it
      document.addEventListener("DOMContentLoaded", function () {
         const urlParams = new URLSearchParams(window.location.search);
         if (urlParams.has('filtro_nome')) {
            const input = document.querySelector('input[onkeyup*="filtro_nome"]');
            if (input) {
               input.focus();
               // Move cursor to end
               const val = input.value;
               input.value = '';
               input.value = val;
            }
         } else if (urlParams.has('filtro_email')) {
            const input = document.querySelector('input[onkeyup*="filtro_email"]');
            if (input) input.focus();
         }
      });
   </script>
@endsection