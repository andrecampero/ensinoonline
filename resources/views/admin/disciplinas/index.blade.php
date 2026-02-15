@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-book me-2 text-primary"></i> Gerenciamento de Disciplinas</h3>
        <div class="d-flex gap-2">
            <form action="{{ route('disciplinas.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start rounded-pill border-end-0 ps-3"
                    placeholder="Buscar por título, descrição..." value="{{ request('search') }}"
                    style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                <button class="btn btn-outline-secondary rounded-end rounded-pill border-start-0 pe-3" type="submit"
                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            @if(auth()->user()->role === 'admin')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i> Nova Disciplina
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
                            <th>Descrição</th>
                            <th>Curso</th>
                            <th>Professor</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="text-end pe-4">Ações</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disciplinas as $disciplina)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $disciplina->titulo }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($disciplina->descricao, 40) }}</span>
                                </td>
                                <td>
                                    @if($disciplina->curso)
                                        <span class="badge bg-light text-dark border">{{ $disciplina->curso->titulo }}</span>
                                    @else
                                        <span class="text-muted italic">Não vinculado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina->usuario)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-soft rounded-circle text-primary me-2 d-flex align-items-center justify-content-center"
                                                style="width: 24px; height: 24px; font-size: 10px; background-color: rgba(90, 90, 90, 0.1);">
                                                {{ substr($disciplina->usuario->name, 0, 1) }}
                                            </div>
                                            <span>{{ $disciplina->usuario->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted italic">Não definido</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light text-primary border-0 me-2" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $disciplina->id }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('disciplinas.destroy', $disciplina) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger border-0"
                                                onclick="return confirm('Tem certeza que deseja excluir esta disciplina?')"
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
                                    <br>Nenhuma disciplina cadastrada no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($disciplinas as $disciplina)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $disciplina->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Editar Disciplina</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('disciplinas.update', $disciplina) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body py-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título *</label>
                                <input type="text" name="titulo" class="form-control rounded-3"
                                    value="{{ $disciplina->titulo }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descrição</label>
                                <textarea name="descricao" class="form-control rounded-3"
                                    rows="2">{{ $disciplina->descricao }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Curso</label>
                                <select name="curso_id" class="form-select rounded-3" required>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ $disciplina->curso_id == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Professor</label>
                                <select name="usuarios_id" class="form-select rounded-3" required>
                                    @foreach($professores as $professor)
                                        <option value="{{ $professor->id }}" {{ $disciplina->usuarios_id == $professor->id ? 'selected' : '' }}>
                                            {{ $professor->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                    <h5 class="modal-title fw-bold">Nova Disciplina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('disciplinas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título *</label>
                            <input type="text" name="titulo" class="form-control rounded-3"
                                placeholder="Ex: Introdução à Genética" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descrição</label>
                            <textarea name="descricao" class="form-control rounded-3" rows="2"
                                placeholder="Breve descrição da disciplina..."></textarea>
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
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Professor *</label>
                            <select name="usuarios_id" class="form-select rounded-3" required>
                                <option value="">Selecione um professor...</option>
                                @foreach($professores as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Cadastrar Disciplina</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection