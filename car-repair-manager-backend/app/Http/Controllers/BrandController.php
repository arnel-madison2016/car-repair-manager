<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BrandController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $brand = Brand::with('manufacturers')->get();

        return response()->json($brand);
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
                $validated = $request->validate([
                    'name' => 'required|string|max:255|unique:brands,name',
                ]);
                
                // storing the datas in to db
                $brand = Brand::create([
                    'name' => $request->name,
                    'manufacturer_id' => $request->manufacturer_id
                ]);

                return response()->json([
                    'status' => 201,
                    'brand' => $brand
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $brand = Brand::with('manufacturer')->find($id);

        return response()->json($brand);
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
                    'name' => 'required|string|max:255|unique:brands,name',
                ]);
                
                // found the record to update
                $brand = Brand::find($id);

                // update record
                if($brand){            
                    $brand->update([
                        'name' => $request->name,
                        'manufacturer_id' => $request->manufacturer_id
                    ]);

                    return response()->json([
                        'status' => 200,
                        'brand' => $brand
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

                // found the record to update
                $brand = Brand::find($id);                

                if($brand){

                    $label = $brand->name;

                    // deletion
                    $brand->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => ucfirst($label) . ' has successfully being deleted.'
                    ]);
                }
            }
        }
    }
}
