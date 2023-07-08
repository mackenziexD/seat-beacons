@extends('web::layouts.grids.12', ['viewname' => 'seat-beacons::index'])

@section('page_header', 'Beacons Dashboard')

@section('full')
    <div class="card">
        <div class="card-header">Beacons</div>
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-4" onclick="exportTableToCSV('fuel')">Export to CSV</button>
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
                            <td>{{ $beacon->info->name }}</td>
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

@stop