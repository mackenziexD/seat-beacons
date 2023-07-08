@extends('web::layouts.grids.12', ['viewname' => 'seat-beacons::index'])

@section('page_header', 'Beacons Dashboard')

@section('full')
    <div class="card">
        <div class="card-header">Beacons</div>
        <div class="card-body">
            {{ $dataTable->table(['id' => 'beacons-table', 'class' => 'table table-hover']) }}
        </div>
    </div>
@stop

@push('javascript')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {{ $dataTable->scripts() }}
@endpush
