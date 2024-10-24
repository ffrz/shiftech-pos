<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class TrackServiceController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        return view('public.track-service.index', compact('data'));
    }

    public function track(Request $request, $id)
    {
        $id = decrypt_id($id);
        $serviceOrder = ServiceOrder::findOrFail($id);
        return view('public.track-service.track', compact('serviceOrder'));
    }
}
