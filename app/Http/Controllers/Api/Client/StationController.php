<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Http\Resources\StationCollection;

class StationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $stations = Station::search($search)
            ->latest()
            ->paginate();

        return new StationCollection($stations);
    }
}
