<?php

namespace App\Http\Controllers;

use App\Models\RepairSheet;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairSheetController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);

            }

            // list repairsheets
            $repairSheets = RepairSheet::with(['vehicule', 'tasks'])->get();

            return response()->json([
                'repairsheets' => $repairSheets,
            ], 200);

        }else{

            // unauthorized action
            return response()->json([
                'message' => 'You must first log in to your user account.',
            ], 404);
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

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);

            }

            $data = $request->validate([
                'vehicule_id' => 'required|exists:vehicules,id',
                'probleme_reporte' => 'required|string',
                'date_arrivee' => 'required|date',
                'date_sortie' => 'nullable|date',
                'status' => 'required|in:pending,in_process,delivered',
            ]);

            $sheet = RepairSheet::create($data);

            return response()->json($sheet, 201);
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

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);

            }

            $sheet = RepairSheet::with('tasks.service', 'tasks.mechanic', 'tasks.vehicule')->findOrFail($id);

            return response()->json($sheet);
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

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);
            }

            $data = $request->validate([
                'vehicule_id' => 'sometimes|exists:vehicules,id',
                'probleme_reporte' => 'sometimes|string',
                'date_arrivee' => 'sometimes|date',
                'date_sortie' => 'nullable|date',
                'status' => 'sometimes|in:pending,in_process,delivered',
            ]);

            $repairSheet = RepairSheet::findOrFail($id);

            // updating sheet
            $repairSheet->update($data);

            return response()->json([
                'repairsheet' => $repairSheet,
            ], 201);
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
                    'message' => 'Access denied.'
                ], 403);
            }
        }

        $repairSheet = RepairSheet::findOrFail($id);

        // delete first tasks that belongs to
        $repairSheet->tasks()->delete();

        // then delete the repairsheet
        $repairSheet->delete();

        return response()->json([
            'message' => 'Repair sheet deleted'
        ], 201);
    }
}
