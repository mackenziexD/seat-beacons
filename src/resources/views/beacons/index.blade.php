@extends('web::layouts.grids.12', ['viewname' => 'seat-beacons::index'])

@section('page_header', 'Beacons Dashboard')

@section('full')
    <div class="card">
        <div class="card-header">Beacons</div>
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-4" onclick="exportTableToCSV('fuel')">Export to CSV</button>
            <button type="button" class="btn btn-primary mb-4" onclick="selectElementContents( document.getElementById('fuel') );">Export to Text</button>
            <table id="fuel" class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Constellation</th>
                        <th>Region</th>
                        <th>Fuel Expires In (Days)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($beacons as $beacon)
                        <tr>
                            <td>{{ $beacon->solar_system->name }}</td>
                            <td>{{ $beacon->solar_system->constellation->name }}</td>
                            <td>{{ $beacon->solar_system->constellation->region->name }}</td>
                            @if($beacon->state !== 'shield_vulnerable')
                                <td data-order="0FFLINE">{{ $beacon->state }}</td>
                            @else
                                <td data-order="{{ $beacon->fuel_expires }}">{{ \Carbon\Carbon::parse($beacon->fuel_expires)->diffInDays() }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        function exportTableToCSV(table_id, separator = ',') {
            // expend datatable 
            var table = $('#' + table_id).DataTable();
            // redraw table with all data
            table.destroy();
            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }
            var csv_string = csv.join('\n');
            // Download it
            var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            // set table to original state
            var table = $('#fuel').DataTable( {
                        "oLanguage": {
                        "sLengthMenu": "Show  _MENU_",
                        },
                        "orderable": true,
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Show All"]],
                        "order": [[ 4, "asc" ]],
                    });
        }
        // create function that will copy the table to clipboard
        function selectElementContents(el) {
            // expend datatable 
            var table = $('#fuel').DataTable();
            table.destroy();
            var body = document.body, range, sel;
            if (document.createRange && window.getSelection) {
                range = document.createRange();
                sel = window.getSelection();
                sel.removeAllRanges();
                try {
                    range.selectNodeContents(el);
                    sel.addRange(range);
                } catch (e) {
                    range.selectNode(el);
                    sel.addRange(range);
                }
            } else if (body.createTextRange) {
                range = body.createTextRange();
                range.moveToElementText(el);
                range.select();
            }
            document.execCommand("Copy");
            var table = $('#fuel').DataTable( {
                        "oLanguage": {
                        "sLengthMenu": "Show  _MENU_",
                        },
                        "orderable": true,
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Show All"]],
                        "order": [[ 4, "asc" ]],
                    });
        }
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#fuel').DataTable( {
                "oLanguage": {
                "sLengthMenu": "Show  _MENU_",
                },
                "orderable": true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Show All"]],
                "order": [[ 3, "asc" ]],
            });

            // find any rows with text "OFFLINE"
            var row = table.rows( function ( idx, data, node ) {
                return data[4] === "0FFLINE" || data[4] === "0FFLINE **[INCURSION]**";
            } );
            row.nodes().to$().prependTo( '#fuel tbody' );
            row.nodes().to$().addClass( 'danger' );

            // move any 3 digit numbers to bottom of table
            var row = table.rows( function ( idx, data, node ) {
                return data[4].length === 3;
            } );

            // every time table is redrawn, check for OFFLINE rows
            table.on( 'draw', function () {
                // find any rows with text "OFFLINE"
                var row = table.rows( function ( idx, data, node ) {
                    return data[4] === "0FFLINE" || data[4] === "0FFLINE **[INCURSION]**";
                } );
                row.nodes().to$().prependTo( '#fuel tbody' );
                row.nodes().to$().addClass( 'danger' );
            } );

        });
    </script>

@stop