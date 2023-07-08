<?php

namespace Helious\SeatBeacons\Http\Controllers;

use Seat\Eveapi\Models\Corporation\CorporationStructure;

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
     public function index(Request $request)
    {
        $beacons = CorporationStructure::where('type_id', '35840')->get();
        return view('seat-beacons::beacons.index', compact('beacons'));
    }


}
