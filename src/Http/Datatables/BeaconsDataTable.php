<?php

namespace Helious\SeatBeacons\Http\Datatables;

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
            ->editColumn('fuel_expires_in_days', function ($row) {
                return \Carbon\Carbon::parse($row->fuel_expires)->diffInDays();
            });
    }

    public function query(CorporationStructure $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('beacons-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    protected function getColumns()
    {
        return [
            'name',
            'constellation',
            'region',
            'fuel_expires_in_days'
        ];
    }

    protected function filename()
    {
        return 'Beacons_' . date('YmdHis');
    }
}