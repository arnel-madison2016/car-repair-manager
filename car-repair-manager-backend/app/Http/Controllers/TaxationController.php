<?php

namespace App\Http\Controllers;

use App\Models\Taxation;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaxationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $taxations = Taxation::with('service')->get();

        return response()->json($taxations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        
        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                abort(403, 'Only administrator or manager can manage a taxation.');
            }

            $taxation = Taxation::where('service_id', $request->service_id)->get();

            if($taxation){

                // line taxation already booked
                abort(403, 'Line already exists');
            }

            // datas validation
            $data = $request->validate([
                'service_id' => 'required|exists:services,id',
                'cout_prestation' => 'required|decimal',
                'reduction' => 'sometimes|decimal',
            ]);

            // saving datas
            $taxation = Taxation::create($data);

            return response()->json([
                'taxation' => $taxation,
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $taxation = Taxation::findOrFail($id);

        return response()->json([
            'taxation' => $taxation,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        
        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                abort(403, 'Only administrator or manager can update a taxation.');
            }

            $taxation = Taxation::findOrFail($id);

            if(!$taxation){
                // not found !
                abort(404, 'Taxation not found.');
            }

            // datas validation
            $data = $request->validate([
                'service_id' => 'required|exists:services,id',
                'cout_prestation' => 'required|decimal',
                'reduction' => 'sometimes|decimal',
            ]);

            $taxation->update($data);

            return response()->json([
                'taxation' => $taxation,
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        
        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);

            // check if it's admin role ?
            if (!$user->hasRole('admin')) {
                // unauthorized action
                abort(403, 'Only administrator or manager can delete a taxation.');
            }

            $taxation = Taxation::findOrFail($id);

            $taxation->delete();

            return response()->json([
                'message' => 'Taxation has successfully being deleted.',
            ], 201);
        }
    }
}
