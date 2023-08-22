<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->name;


        return view('users.index', [
            'user' => $user,
        ]);
    }

    public function getUsers()
    {
        // get user data with permission name
        $users = User::with('permissions')->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    public function getPermissions()
    {        
        $permissions = DB::table('permissions')
        ->orderBy('remark', 'asc')
        ->get();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }

    // getPermissionsUser
    public function getPermissionsUser($username)
    {        

    }


    public function create()
    {
        $user = Auth::user()->name;

        return view('users.create', [
            'user' => $user,
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
            $username = $request->username;
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;

            $request_permission = $request->permission;                        

            // dd($request->all());
            
            DB::beginTransaction();
            try {
                $user = User::create([
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
    
                ]);

                // $user->assignPermission($request_permission);
                $user->givePermissionTo($request_permission);                                
                

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'message' => 'User already exists'
                ]);                
            }
    
            return response()->json([
                'user' => $user,
                'message' => 'User created successfully'
            ]);
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


    public function edit(Request $request, $username)
    {         
        $auth_username = Auth::user()->name;        

        $user = User::with('permissions')->where('username', $username)->first();

        if(!$user) {
            abort(404);
        }        
        
        return view('users.edit', [            
            'user' => $user,
        ]);
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
            $user = User::findOrFail($id);

            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if($request->password) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $user->syncPermissions($request->permission);

            return response()->json([
                'user' => $user,
                'message' => 'User updated successfully'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not found'
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
        //
    }
}
