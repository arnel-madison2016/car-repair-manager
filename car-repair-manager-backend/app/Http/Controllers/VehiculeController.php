<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicule;

class VehiculeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(){

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin role ?
            if (!$user->hasRole('admin')) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);

            }

            // list vehicules
            $vehicules = Vehicule::with('customer')->get();

            return response()->json([
                'status' => 200,
                'vehicules' => $vehicules,
            ]);

        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
        }
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

            // get customer id
            $customer = Customer::find($request->customer_id);

            // check if it's admin role ?
            if (!$user->hasRole('client') || $user->id !== $customer->user_id) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            // vehicle already exists ?
            $vehicule = Vehicule::where('chassis_number', $request->chassis_number)->first(); 

            if(!$vehicule){

                // validate vehicule datas
                $request->validate([
                    // ------------- vehicule ------------------
                    'license_plate' => 'required|string|max:150|unique:vehicules,license_plate',
                    'chassis_number' => 'required|string|max:150',
                    'brand_id' => 'required|numeric',
                    'odometer_reading' => 'required|string|max:50',
                    'year_registration' => 'required|string|max:4',
                    'fuel_type' => 'required|string|max:50',
                    'vehicle_type' => 'required|string|max:50',
                    'gear_box' => 'required|string|max:50',
                    'engine_size' => 'required|string|max:50',    
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',            
                ]);

                // manage vehicule images
                if($request->hasFile('image')){

                    // get image details
                    $file = $request->file('image');
                    $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                    // path where to store image "storage/app/public/images/vehicules"
                    $filePath = $file->storeAs('images/vehicules', $fileName, 'public');
                }

                // storing vehicule datas in to db
                $vehicule = $customer->vehicules()->create([
                    'brand_id' => $request->brand_id,
                    'license_plate' => $request->licence_plate,
                    'chassis_number' => $request->chassis_number,
                    'odometer_reading' => $request->odometer_reading,
                    'year_registration' => $request->year_registration,
                    'fuel_type' => $request->fuel_type,
                    'vehicle_type' => $request->vehicle_type,
                    'gear_box' => $request->gear_box,
                    'engine_size' => $request->engine_size,
                    'url_pictures' => $filePath
                ]);
                
                return response()->json([
                    'status' => 200,
                    'vehicule' => $vehicule,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // get vehicule datas
            $vehicle = Vehicule::with(['brand', 'customer'])->findOrFail($id);

            // check if it's admin or client role and it's the owner ?
            if (!$user->hasAnyRole(['admin','client']) || $user->id !== $vehicle->customer->user_id) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            return response()->json([
                'status' => 200,
                'vehicule' => $vehicle,
            ]);

        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
        }
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

            // get vehicule datas
            $vehicle = Vehicule::with(['brand', 'customer'])->findOrFail($id);

            // check if it's admin or client role and it's the owner ?
            if (!$user->hasAnyRole(['admin','client']) || $user->id !== $vehicle->customer->user_id) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            // validate vehicule datas
            $request->validate([
                // ------------- vehicule ------------------
                'license_plate' => 'required|string|max:150',
                'chassis_number' => 'required|string|max:150',
                'brand_id' => 'required|numeric',
                'odometer_reading' => 'required|string|max:50',
                'year_registration' => 'required|string|max:4',
                'fuel_type' => 'required|string|max:50',
                'vehicle_type' => 'required|string|max:50',
                'gear_box' => 'required|string|max:50',
                'engine_size' => 'required|string|max:50',    
                //'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',            
            ]);

            // manage images
            if($request->hasFile('image')){

                if($vehicle){
                    // delete the old image in path: /public/images/vehicules
                    Storage::disk('public')->delete($vehicle->url_pictures);
                }            

                // get image details
                $file = $request->file('image');
                $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                // path where to store image "storage/app/public/images/vehicules"
                $filePath = $file->storeAs('images/vehicules', $fileName, 'public');
            }

            // storing vehicule datas in to db
            $vehicle->update([
                'customer_id' => $request->customer_id,
                'brand_id' => $request->brand_id,
                'license_plate' => $request->licence_plate,
                'chassis_number' => $request->chassis_number,
                'odometer_reading' => $request->odometer_reading,
                'year_registration' => $request->year_registration,
                'fuel_type' => $request->fuel_type,
                'vehicle_type' => $request->vehicle_type,
                'gear_box' => $request->gear_box,
                'engine_size' => $request->engine_size,
                'url_pictures' => $filePath
            ]);            

            return response()->json([
                'status' => 200,
                'vehicule' => $vehicle,
            ]);

        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
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
            if (!$user->hasRole('admin')) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            // found the record to destroy
            $vehicle = Vehicule::find($id);

            if($vehicle){

                // delete physically vehicle image
                if($vehicle->url_pictures){

                    // delete the old image in path: /public/images/vehicules
                    Storage::disk('public')->delete($vehicle->url_pictures);
                }

                // delete the record in the database
                $vehicle->delete();

                return [
                    'status' => 201,
                    'message' => 'Record has successfully being deleted.'
                ];
            }
        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
        }
    }
}
