<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Manufacturer;
use App\Models\User;

class ManufacturerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $manufacturer = Manufacturer::all();

        return response()->json($manufacturer);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'manager'])) {

                // validate datas
                $request->validate([
                    'name' => 'required|string|max:255|unique:manufacturers,name',                    
                    'country' => 'required'
                ]);                

                // storing the datas in to db
                $manufacturer = Manufacturer::create([
                    'name' => $request->name,
                    'country' => $request->country,
                ]);

                return response()->json([
                    'status' => 201,
                    'car_manufacturer' => $manufacturer
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $manufacturer = Manufacturer::find($id);
        
        return response()->json($manufacturer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'manager'])) {

                // find the record to update
                $manufacturer = Manufacturer::find($id);

                // initialize the path to store images locally
                $filePath = '';        

                // validate datas
                $request->validate([
                    'name' => 'required|string|max:255|unique:car_manufacturers,name',                    
                    'country' => 'required'
                ]);       
                                
                // update record
                $manufacturer->update([
                    'name' => $request->name,
                    'country' => $request->country,
                    'url_picture' => $filePath
                ]);

                return response()->json([
                    'status' => 200,
                    'manufacturer' => $manufacturer
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin role ?
            if ($user->hasRole('admin')) {

                // found the record to destroy
                $manufacturer = Manufacturer::find($id);

                if($manufacturer){

                    // delete the record in the database
                    $manufacturer->delete();

                    return [
                        'status' => 201,
                        'message' => 'Record successfully deleted.'
                    ];
                }
            }
        }
    }
}
