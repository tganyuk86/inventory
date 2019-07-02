<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Floor;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home', [
            'floors' => Floor::all()
        ]);
    }

    public function viewFloor($id)
    {

        return view('floor', [
            'floor' => Floor::find($id)
        ]);
    }
    public function saveFloorData(Request $request)
    {

        // dd($request);

        $floor = Floor::find($request['id']);

        $floor->data = $request['d'];

        $floor->save();

        echo 1;
    }
}
