@extends('layouts.app')

@section('content')
   <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="fas fa-user-graduate me-2 text-primary"></i> Gerenciamento de Alunos</h3>
      <div class="d-flex gap-2">
         <form action="{{ route('alunos.index') }}" method="GET" class="d-flex">
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
               <i class="fas fa-plus me-2"></i> Novo Aluno
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
                     <th class="ps-4">Nome</th>
                     <th>Email</th>
                     <th>Data de Nascimento</th>
                     <th>Matrículas</th>
                     @if(auth()->user()->role === 'admin')
                        <th class="text-end pe-4">Ações</th>
                     @endif
                  </tr>
               </thead>
               <tbody>
                  @forelse($alunos as $aluno)
                     <tr>
                        <td class="ps-4">
                           <div class="d-flex align-items-center">
                              <div
                                 class="avatar-sm bg-success-soft rounded-circle text-success me-2 d-flex align-items-center justify-content-center"
                                 style="width: 32px; height: 32px; background-color: rgba(0, 182, 122, 0.1);">
                                 {{ substr($aluno->name, 0, 1) }}
                              </div>
                              <span class="fw-semibold text-dark">{{ $aluno->name }}</span>
                           </div>
                        </td>
                        <td>
                           <span class="text-muted">{{ $aluno->email }}</span>
                        </td>
                        <td>
                           @if($aluno->data_nascimento)
                              <i class="far fa-calendar-alt me-1 text-muted"></i>
                              {{ \Carbon\Carbon::parse($aluno->data_nascimento)->format('d/m/Y') }}
                           @else
                              <span class="text-muted italic">Não informada</span>
                           @endif
                        </td>
                        <td>
                           <span class="badge bg-light text-dark border">
                              {{ $aluno->matriculas->count() }} cursos
                           </span>
                        </td>
                        @if(auth()->user()->role === 'admin')
                           <td class="text-end pe-4">
                              <button class="btn btn-sm btn-light text-primary border-0 me-2" data-bs-toggle="modal"
                                 data-bs-target="#editModal{{ $aluno->id }}" title="Editar">
                                 <i class="fas fa-edit"></i>
                              </button>
                              <form action="{{ route('alunos.destroy', $aluno) }}" method="POST" class="d-inline">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-sm btn-light text-danger border-0"
                                    onclick="return confirm('Tem certeza que deseja excluir este aluno? Todas as matrículas dele também serão removidas.')"
                                    title="Excluir">
                                    <i class="fas fa-trash"></i>
                                 </button>
                              </form>
                           </td>
                        @endif
                     </tr>

                  @empty
                     <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                           <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                           <br>Nenhum aluno encontrado.
                        </td>
                     </tr>
                  @endforelse
               </tbody>
            </table>
         </div>
         <div class="d-flex justify-content-center mt-4">
            {{ $alunos->appends(request()->query())->links() }}
         </div>
      </div>
   </div>

   @foreach($alunos as $aluno)
      <!-- Edit Modal -->
      <div class="modal fade" id="editModal{{ $aluno->id }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
               <div class="modal-header border-0 pb-0">
                  <h5 class="modal-title fw-bold">Editar Aluno</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form action="{{ route('alunos.update', $aluno) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body py-4">
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Nome *</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $aluno->name }}" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ $aluno->email }}" required>
                     </div>
                     <div class="mb-3">
                        <label class="form-label fw-semibold">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control rounded-3"
                           value="{{ $aluno->data_nascimento }}">
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
               <h5 class="modal-title fw-bold">Novo Aluno</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alunos.store') }}" method="POST">
               @csrf
               <div class="modal-body py-4">
                  <div class="alert alert-info border-0 rounded-3 mb-3">
                     <i class="fas fa-info-circle me-2"></i> A senha padrão para novos alunos será:
                     <strong>Mudar@@123</strong>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Nome *</label>
                     <input type="text" name="name" class="form-control rounded-3" placeholder="Ex: Maria Oliveira"
                        required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Email *</label>
                     <input type="email" name="email" class="form-control rounded-3" placeholder="Ex: maria@email.com"
                        required>
                  </div>
                  <div class="mb-3">
                     <label class="form-label fw-semibold">Data de Nascimento</label>
                     <input type="date" name="data_nascimento" class="form-control rounded-3">
                  </div>
               </div>
               <div class="modal-footer border-0 pt-0">
                  <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary rounded-3 px-4">Cadastrar Aluno</button>
               </div>
            </form>
         </div>
      </div>
   </div>
@endsection