<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaSuperAdminController extends Controller
{
    public function index(){
        return view('superadmin.beranda_index',[
            'title' => 'Selamat Datang ',
        ]);
     }

     public function chat(){
        return view('superadmin.chat', [
            'title' => 'Chat Masyarakat',
        ]);
     }

}
