<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\User;

class ServiceController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {

        $services = Service::with('category_service', 'name');

        return response()->json($services);
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
                    'name' => 'required|string|max:255|unique:services,name',
                ]);

                // storing the datas in to db
                $service = Service::create([
                    'name' => $request->name,
                    'category_service_id' => $request->category_service_id,
                    'details' => $request->details,
                ]);

                return response()->json([
                    'status' => 201,
                    'service' => $service
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $services = Service::with('category_services')->find($id);

        return response()->json($services);
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
                $request->validate([
                    'name' => 'required|string|max:255|unique:services,name',
                ]);

                $service = Service::find($id);

                // record found ?
                if($service){

                    // update record
                    $service->update([
                        'name' => $request->name,
                        'category_service_id' => $request->category_service_id,
                        'details' => $request->details,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'service' => $service
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

                // found service to delete
                $service = Service::find($id);

                $label = $service->name;

                if($service){

                    // remove the record
                    $service->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => ucfirst($label) . ' has successfully being deleted.'
                    ]);
                }
            }
        }
    }
}
