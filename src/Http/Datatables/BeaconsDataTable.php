<?php

namespace Helious\SeatBeacons\Http\Datatables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Seat\Eveapi\Models\Corporation\CorporationStructure;

class BeaconsDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', function ($row) {
                return $row->info->name;
            })
            ->editColumn('constellation', function ($row) {
                return $row->solar_system->constellation->name;
            })
            ->editColumn('region', function ($row) {
                return $row->solar_system->constellation->region->name;
            })
            ->editColumn('fuel_expires', function ($row) {
                if($row->services->first()->state === 'offline') return 'Offline';
                return \Carbon\Carbon::parse($row->fuel_expires)->diffInDays();
            });
    }

    public function query(CorporationStructure $model)
    {
        return $model->query()->where('type_id', '35840');
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('beacons-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(3, 'asc')
                    ->buttons(
                        Button::make('csv')
                        ->text('<i class="fas fa-file-csv"></i> Export CSV')
                    )
                    ->parameters([
                        'createdRow' => "function ( row, data, dataIndex ) {
                            if ( data['fuel_expires'] === 'Offline' ) {
                                $(row).addClass('bg-danger');
                            }
                            else if ( data['fuel_expires'] <= 7 ) {
                                $(row).addClass('bg-warning');
                            }
                        }",
                    ]);
    }

    protected function getColumns()
    {
        return [
            'name',
            'constellation',
            'region',
            ['data' => 'fuel_expires', 'title' => 'Fuel Expires (days)']
        ];
    }

    protected function filename()
    {
        return 'Beacons_' . date('YmdHis');
    }
}