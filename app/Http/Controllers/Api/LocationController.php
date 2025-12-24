<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries()
    {
        return response()->json([
            'data' => Country::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function states(Request $request)
    {
        $request->validate([
            'countryid' => ['required', 'integer', 'exists:countries,id'],
        ]);

        return response()->json([
            'data' => State::query()
                ->select('id', 'name', 'countryid')
                ->where('countryid', $request->integer('countryid'))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function cities(Request $request)
    {
        $request->validate([
            'stateid' => ['required', 'integer', 'exists:states,id'],
        ]);

        return response()->json([
            'data' => City::query()
                ->select('id', 'name', 'stateid')
                ->where('stateid', $request->integer('stateid'))
                ->orderBy('name')
                ->get(),
        ]);
    }
}
