<?php

namespace Helious\SeatBeacons\Http\Controllers;

use Helious\SeatBeacons\Http\Datatables\BeaconsDataTable;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeaconsController extends Controller
{
    /**
     * Show the eligibility checker.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BeaconsDataTable $dataTable)
    {
        return $dataTable->render('seat-beacons::beacons.index');
    }

}
