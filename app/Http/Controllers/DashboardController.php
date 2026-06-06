<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->username = [
            'OS0005',
            'OS0006',
            'OS0056',
            'OS0057',
            'OS0070',
            'OS0078',
            'OS0080',
            'OS0081',
            'OS0086',
            'OS0090',
            'OS0093',
            'OS0096',
            'OS0100',
            'OS0101',
            'OS0105',
            'OS0110',
            'OS0111',
            'PI0203011',
            'PI2008009',
            'PI2304053',
            'PI2404023',
            'PI9803004',
            'OS0112',
            'OS0114',
            'OS0113',
   
        ];

        $this->driver_reguler = [
            'OS0005',
            'OS0115',

        ];
        
        if(Auth::user()->username == 'ympimis')
        {
            return view('admin.dashboard')
            ->with('username',strtoupper(Auth::user()->username))
            ->with('all_username',$this->username)
            ->with('driver_reguler',$this->driver_reguler);
        }
        else {
            return view('dashboard')
            ->with('username',strtoupper(Auth::user()->username))
            ->with('all_username',$this->username)
            ->with('driver_reguler',$this->driver_reguler);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
