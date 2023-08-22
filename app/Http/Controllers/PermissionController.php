<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user()->name;        

        $permissions = DB::table('permissions')
        ->orderBy('remark', 'asc')
        ->get();        
        
        return view('users.permission', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {                                    
            $permission = Permission::create([
                'name' => $request->name,
                'guard' => 'web',
            ]);
            
            DB::table('permissions')->where('id', $permission->id)->update([
                'remark' => $request->remark,
            ]);

            return response()->json([
                'message' => 'Permission created successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([                
                'message' => $th->getMessage()
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        try {
                Permission::where('id', $id)->update([
                    'name' => $request->name,
                ]);
                
                if ($request->remark) {
                    DB::table('permissions')->where('id', $id)->update([
                        'remark' => $request->remark,
                    ]);
                }
    
                return response()->json([
                    'message' => 'Permission updated successfully',
                ]);            

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::find($id);
            $permission->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ]);            
        }        
    }
}
