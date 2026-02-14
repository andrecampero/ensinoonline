@extends('layouts.app')

@section('content')
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="fas fa-book-open me-2 text-primary"></i> Gerenciamento de Cursos</h3>
      <div class="d-flex gap-2">
         <form action="{{ route('cursos.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-start rounded-pill border-end-0 ps-3"
               placeholder="Buscar por título ou área..." value="{{ request('search') }}"
               style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
            <button class="btn btn-outline-secondary rounded-end rounded-pill border-start-0 pe-3" type="submit"
               style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
               <i class="fas fa-search"></i>
            </button>
         </form>
         @if(auth()->user()->role === 'admin')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
               <i class="fas fa-plus me-2"></i> Novo Curso
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
                     <th class="ps-4">Título</th>
                     <th>Área</th>
                     <th>Período</th>
                     @if(auth()->user()->role === 'admin')
                        <th class="text-end pe-4">Ações</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
                  @forelse($cursos as $curso)
                     <tr>
                        <td class="ps-4">
                           <span class="fw-semibold text-dark">{{ $curso->titulo }}</span>
                           <br>
                           <small class="text-muted">{{ Str::limit($curso->descricao, 50) }}</small>
                        </td>
                        <td>
                           <span class="badge rounded-pill bg-primary-soft text-primary px-3 py-2"
                              style="background-color: rgba(90, 90, 90, 0.1);">
                              {{ $curso->area }}
                           </span>
                        </td>
                        <td>
                           @if($curso->data_inicio)
                              <i class="far fa-calendar-alt me-1 text-muted"></i>
                              {{ \Carbon\Carbon::parse($curso->data_inicio)->format('d/m/Y') }}
                              @if($curso->data_fim)
                                 - {{ \Carbon\Carbon::parse($curso->data_fim)->format('d/m/Y') }}
                              @endif
                           @else
                              <span class="text-muted italic">Não definido</span>
                           @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                           <td class="text-end pe-4">
                              <button class="btn btn-sm btn-light text-primary border-0 me-2" data-bs-toggle="modal"
                                 data-bs-target="#editModal{{ $curso->id }}" title="Editar">
                                 <i class="fas fa-edit"></i>
                              </button>
                              <form action="{{ route('cursos.destroy', $curso) }}" method="POST" class="d-inline">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-sm btn-light text-danger border-0"
                                    onclick="return confirm('Tem certeza que deseja excluir este curso?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                 </button>
                              </form>
                           </td>
                        @endif
                     </tr>

                  @empty
                     <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                           <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                           <br>Nenhum curso cadastrado no momento.
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>

   @foreach($cursos as $curso)
      <!-- Edit Modal -->
      <div class="modal fade" id="editModal{{ $curso->id }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
               <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold">Editar Curso</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form action="{{ route('cursos.update', $curso) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body py-4">
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Título *</label>
                        <input type="text" name="titulo" class="form-control rounded-3" value="{{ $curso->titulo }}" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Área *</label>
                        <input type="text" name="area" class="form-control rounded-3" value="{{ $curso->area }}"
                           placeholder="Ex: Biologia, Química, Física" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Descrição</label>
                        <textarea name="descricao" class="form-control rounded-3" rows="3">{{ $curso->descricao }}</textarea>
                     </div>
                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <label class="form-label fw-semibold">Data de Início</label>
                           <input type="date" name="data_inicio" class="form-control rounded-3"
                              value="{{ $curso->data_inicio }}">
                        </div>
                        <div class="col-md-6 mb-3">
                           <label class="form-label fw-semibold">Data de Fim</label>
                           <input type="date" name="data_fim" class="form-control rounded-3" value="{{ $curso->data_fim }}">
                        </div>
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
               <h5 class="modal-title fw-bold">Novo Curso</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('cursos.store') }}" method="POST">
               @csrf
               <div class="modal-body py-4">
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Título *</label>
                     <input type="text" name="titulo" class="form-control rounded-3" placeholder="Ex: Química Orgânica"
                        required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Área *</label>
                     <input type="text" name="area" class="form-control rounded-3"
                        placeholder="Ex: Biologia, Química, Física" required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Descrição</label>
                     <textarea name="descricao" class="form-control rounded-3" rows="3"
                        placeholder="Breve resumo sobre o curso..."></textarea>
                  </div>
                  <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Data de Início</label>
                        <input type="date" name="data_inicio" class="form-control rounded-3">
                     </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Data de Fim</label>
                        <input type="date" name="data_fim" class="form-control rounded-3">
                     </div>
                  </div>
               </div>
               <div class="modal-footer border-0 pt-0">
                  <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary rounded-3 px-4">Cadastrar Curso</button>
               </div>
            </form>
         </div>
      </div>
   </div>
@endsection