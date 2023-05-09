<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\GetEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function create(CreateEventRequest $request)
    {
        $data = $request->all();
        $data['ip_address'] = $request->ip();

        return response()->json(['data' => Event::create($data)]);
    }

    public function get(GetEventRequest $request)
    {
        $counter = $request->get('counter');

        $events = Event::groupBy($counter)->select($counter, DB::raw('count(*) as total'));

        if ($request->filled('date')) {
            $events->whereDate('created_at', '=', $request->get('date'));
        }

        if ($request->filled('name')) {
            $events->where('name', '=', $request->get('name'));
        }

        return response()->json(['data' => $events->get()]);
    }
}
