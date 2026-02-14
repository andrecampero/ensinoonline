@extends('layouts.app')

@section('content')
   <div class="row mb-4 align-items-center">
      <div class="col">
         <h3 class="fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i> Relatório de Faixa Etária por Curso</h3>
      </div>
      <div class="col-auto">
         <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Voltar
         </a>
      </div>
   </div>

   <div class="card shadow-sm border-0">
      <div class="card-body">
         <div class="table-responsive p-3">
            <table class="table table-hover align-middle mb-0" id="tabelaRelatorio" style="width:100%">
               <thead class="bg-light">
                  <tr>
                     <th class="ps-4">Curso</th>
                     <th>Média de Idade</th>
                     <th>Aluno Mais Novo</th>
                     <th>Aluno Mais Velho</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($dados as $item)
                     <tr>
                        <td class="ps-4 fw-semibold">{{ $item['curso'] }}</td>
                        <td>
                           @if($item['media_idade'])
                              {{ $item['media_idade'] }} anos
                           @else
                              <span class="text-muted italic">Sem dados</span>
                           @endif
                        </td>
                        <td>{{ $item['mais_novo'] }}</td>
                        <td>{{ $item['mais_velho'] }}</td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
@endsection

@section('scripts')
   <!-- DataTables & Plugins -->
   <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

   <!-- Buttons -->
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

   <script>
      $(document).ready(function () {
         $('#tabelaRelatorio').DataTable({
            language: {
               url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            },
            dom: 'Bfrtip',
            buttons: [
               {
                  extend: 'excelHtml5',
                  text: '<i class="fas fa-file-excel me-2"></i> Exportar Excel',
                  className: 'btn btn-success btn-sm rounded-3'
               },
               {
                  extend: 'print',
                  text: '<i class="fas fa-print me-2"></i> Imprimir',
                  className: 'btn btn-light btn-sm rounded-3 border ms-2'
               }
            ],
            pageLength: 25
         });
      });
   </script>
@endsection