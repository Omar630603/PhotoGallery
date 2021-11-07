<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $photos =Photo::where('id_user', Auth::user()->id_user)->get();
        $allPhotos = count($photos);
        $photos =Photo::where('id_user', Auth::user()->id_user)->paginate(10);
        return view('home', ['photos'=>$photos, 'allPhotos' => $allPhotos]);
    }
}
