<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);
            
            if($user->hasAnyRole(['admin', 'manager'])) {
                // check if admin/manager role's
                $tasks = Task::with(['repairsheet', 'service', 'mechanic', 'repairsheet.vehicule'])->get();
            }else{
                // list tasks which belongs to mechanics
                $tasks = Task::with(['repairsheet', 'service', 'mechanic', 'repairsheet.vehicule'])
                            ->where('user_id', $user->id)->get();
            }            

            return response()->json([
                'tasks' => $tasks,
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
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);

            // check if it's admin role ?
            if (!$user->hasAnyRole(['admin', 'manager'])) {
                // unauthorized action
                abort(403, 'Only administrator or manager can update a task.');
            }

            $data = $request->validate([
                'repair_sheet_id' => 'required|exists:repair_sheets,id',
                'service_id' => 'required|exists:services,id',
                'user_id' => 'required|exists:users,id',
                'estimated_time' => 'required|time',
                'date_completed' => 'nullable|date',
                'status' => 'required|in:pending,in_process,completed',
            ]);

            $task = Task::where([
                ['repair_sheet_id', $request->repair_sheet_id],
                ['service_id', $request->service_id],
                ['user_id', $request->user_id]
            ])->firstOrFail();

            if($task){                
                abort(403, 'Task already booked.');                
            }

            $task->create($data);

            return response()->json([
                'task' => $task
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $connected_user = Auth::user();
            $user = User::find($connected_user->id);

            if($user->hasAnyRole(['admin', 'manager'])) {
                // check if admin/manager role
                $tasks = Task::with(['repairsheet', 'service', 'mechanic', 'repairsheet.vehicule'])->get();
            }else{
                // list tasks which belongs to mechanics
                $tasks = Task::with(['repairsheet', 'service', 'mechanic', 'repairsheet.vehicule'])
                ->where('user_id', $user->id)->get();
            }

            return response()->json([
                'task' => $tasks,
            ], 200);
        }
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
                abort(403, 'Only administrator or manager can update a task.');
            }

            $task = Task::findOrFail($id);

            $data = $request->validate([
                'repair_sheet_id' => 'sometimes|exists:repair_sheet,id',
                'service_id' => 'sometimes|exists:services,id',
                'user_id' => 'sometimes|exists:users,id',
                'estimated_time' => 'sometimes|time',
                'date_completed' => 'nullable|date',
                'status' => 'sometimes|in:pending,in_process,completed',
            ]);

            $task->update($data);
            return response()->json([
                'task' => $task
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
                abort(403, 'Only an administrator can delete a task.');
            }

            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'message' => 'Task has successfully being deleted.',
            ], 201);
        }
    }
}
