<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class homecontroller extends Controller
{
  public function index(Request $request){
    $nom = $request->nom;
    return view('salam',[
        'nom'=> $nom
    ]);

  }
}
