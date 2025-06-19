<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $pricing = Pricing::with('services')->get();

        return response()->json($pricing);
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
                    'cout_prestation' => 'required|decimal:2',
                ]);

                // storing the datas in to db
                $pricing = Pricing::create([
                    'service_id' => $request->service_id,
                    'cout_prestation' => $request->cout_prestation,
                    'reduction' => $request->reduction,
                ]);

                return response()->json([
                    'status' => 201,
                    'service' => $pricing
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // found specify price
        $price = Pricing::with('service')->find($id);

        return response()->json($price);
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
                    'cout_prestation' => 'required|decimal:2',
                ]);

                $price = Pricing::find($id);

                // record found ?
                if($price){

                    // update record
                    $price->update([
                        'service_id' => $request->service_id,
                        'cout_prestation' => $request->cout_prestation,
                        'reduction' => $request->reduction,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'service' => $price
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

                // found price to delete
                $price = Pricing::find($id);

                if($price){

                    // remove the record
                    $price->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Record has successfully being deleted.'
                    ]);
                }
            }
        }
    }
}
