<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LevelController extends Controller
{

    public function index()
    {
        // Query users based on their level
        $nol = User::where('level', 0)->get(); 
        $satu = User::where('level', 1)->get();
        $dua = User::where('level', 2)->get();
   

        // Pass the variables to the view
        return view('level.index', compact('nol', 'satu', 'dua'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Return the view with the user data
        return view('level.edit', compact('user'));
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
        // Validate the request
        $request->validate([
            'level' => 'required|integer|between:0,2',
        ]);
    
        // Find the user by ID
        $user = User::findOrFail($id);
    
        // Update the user's level
        $user->update(['level' => $request->level]);
    
    
        // Redirect back with a success message
        return redirect()->route('level.index')->with('success', 'User level updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);
    
        // Delete the user
        $user->delete();
    
    
        // Redirect back with a success message
        return redirect()->route('level.index')->with('success', 'User deleted successfully.');
    }
}
