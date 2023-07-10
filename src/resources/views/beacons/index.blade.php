@extends('web::layouts.grids.12', ['viewname' => 'seat-beacons::index'])

@section('page_header', 'Beacons Dashboard')

@section('full')
<style>
.dataTables_length{
    text-align: right;
}
</style>
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
    <script>
        $.extend(true, $.fn.dataTable.Buttons.defaults, {
            dom: {
                container: {
                    className: 'dt-buttons'
                },
                button: {
                    className: 'btn btn-primary'
                },
                collection: {
                    tag: 'div',
                    className: 'dt-button-collection dropdown-menu',
                    button: {
                        tag: 'a',
                        className: 'dt-button dropdown-item',
                        active: 'active',
                        disabled: 'disabled'
                    }
                }
            }
        });
    </script>
    <script>
        // once the datatable is drawn if any of the 3rd column values are less than 7 days, highlight the row
        $(document).ready(function() {
            var table = $('#beacons-table').DataTable();
            table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data[3] < 7) {
                    $(this.node()).addClass('bg-warning');
                }
                if(data[3] === 'Offline') {
                    $(this.node()).addClass('bg-danger');
                }
            });
        });
    </script>
@endpush
