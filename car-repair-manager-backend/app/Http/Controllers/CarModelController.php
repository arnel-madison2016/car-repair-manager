<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarModelController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {

        $car_models = CarModel::with('brands')->get();

        return response()->json($car_models);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'manager'])) {

                // validate datas
                $validated = $request->validate([
                    'name' => 'required|string|max:255|unique:car_models,name',
                    'category' => 'required|string|max:255',
                ]);
                
                // storing the datas in to db
                $car_model = CarModel::create([
                    'name' => $request->name,
                    'brand_id' => $request->brand_id,
                    'category' => $request->category,
                ]);

                return response()->json([
                    'status' => 201,
                    'car_model' => $car_model
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $car_models = CarModel::with('brands')->find($id);

        return response()->json($car_models);
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

                // validate datas
                $validated = $request->validate([
                    'name' => 'required|string|max:255|unique:car_models,name',
                    'category' => 'required|string|max:255',
                ]);
               
                // found the record to update
                $car_model = CarModel::find($id);

                // update record
                if($car_model){            
                    $car_model->update([
                        'name' => $request->name,
                        'brand_id' => $request->brand_id,
                        'category' => $request->category,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'brand' => $car_model
                    ]);
                }
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

            // check if it's admin or manager role ?
            if ($user->hasRole('admin')) {

                // found the record to delete
                $car_model = CarModel::find($id);                

                if($car_model){
                    
                    $label = $car_model->name;

                    // deletion
                    $car_model->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => ucfirst($label) . ' has successfully being deleted.'
                    ]);
                }
            }
        }
    }
}
