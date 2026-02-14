@extends('layouts.app')

@section('content')
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="fas fa-id-card me-2 text-primary"></i> Gerenciamento de Matrículas</h3>
      <div class="d-flex gap-2">
         <form action="{{ route('matriculas.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control rounded-start rounded-pill border-end-0 ps-3"
               placeholder="Buscar por aluno ou curso..." value="{{ request('search') }}"
               style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
            <button class="btn btn-outline-secondary rounded-end rounded-pill border-start-0 pe-3" type="submit"
               style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
               <i class="fas fa-search"></i>
            </button>
         </form>
         @if(auth()->user()->role === 'admin')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
               <i class="fas fa-plus me-2"></i> Matricular Aluno
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
                     <th class="ps-4">Aluno</th>
                     <th>Curso</th>
                     <th>Status da Matrícula</th>
                     @if(auth()->user()->role === 'admin')
                        <th class="text-end pe-4">Ações</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
                  @forelse($matriculas as $matricula)
                     <tr>
                        <td class="ps-4">
                           @if($matricula->user)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary-soft rounded-circle text-primary me-2 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px; background-color: rgba(90, 90, 90, 0.1);">
                                        {{ substr($matricula->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark">{{ $matricula->user->name }}</span>
                                        <br>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $matricula->user->email }}</small>
                                    </div>
                                </div>
                           @else
                              <span class="text-danger italic">Aluno Removido</span>
                           @endif
                        </td>
                        <td>
                           @if($matricula->curso)
                              <span class="badge bg-light text-primary border">{{ $matricula->curso->titulo }}</span>
                           @else
                              <span class="text-danger italic">Curso Removido</span>
                           @endif
                        </td>
                        <td>
                           <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Ativa</span>
                        </td>
                        @if(auth()->user()->role === 'admin')
                        <td class="text-end pe-4">
                           <form action="{{ route('matriculas.destroy', $matricula) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-light text-danger border-0"
                                 onclick="return confirm('Tem certeza que deseja cancelar esta matrícula?')" title="Excluir">
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
                           <br>Nenhuma matrícula registrada no momento.
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
      </div>
   </div>

   <!-- Create Modal -->
   <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
               <h5 class="modal-title fw-bold">Nova Matrícula</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('matriculas.store') }}" method="POST">
               @csrf
               <div class="modal-body py-4">
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Aluno *</label>
                     <select name="user_id" class="form-select rounded-3" required>
                        <option value="">Selecione um aluno...</option>
                        @foreach($alunos as $aluno)
                           <option value="{{ $aluno->id }}">{{ $aluno->name }} ({{ $aluno->email }})</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Curso *</label>
                     <select name="curso_id" class="form-select rounded-3" required>
                        <option value="">Selecione um curso...</option>
                        @foreach($cursos as $curso)
                           <option value="{{ $curso->id }}">{{ $curso->titulo }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="modal-footer border-0 pt-0">
                  <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary rounded-3 px-4">Realizar Matrícula</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   </div>

   <script>
      function selectAluno(alunoId) {
         const select = document.querySelector('select[name="user_id"]');
         select.value = alunoId;
      }
   </script>
@endsection