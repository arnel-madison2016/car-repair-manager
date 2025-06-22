<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehiculeController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        // current user connected ?
        $account_connected = Auth::user();
        $user = User::find($account_connected->id);

        if ($user->hasRole('admin')) {
            // for admin list all lastest appointments
            $appointments = Appointment::with(['customer', 'vehicule'])->latest()->get();

            return response()->json($appointments);
        }

        // Simple customer case: assumes that a customer is linked to a Customer model
        $customer_id = $user->customer?->id;
        $appointments = Appointment::with(['customer', 'vehicule'])
                            ->where('customer_id', $customer_id)
                            ->latest()
                            ->get();
        
        return response()->json($appointments);
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
            if (!$user->hasRole('client') || $user->id !== $user->customer?->id) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);
            }

            // validate datas infos
            $request->validate([
                // ------------- customer ------------------
                'last_name' => 'required|string|max:255|unique:customers,last_name',
                'gender' => 'required|string|max:1',
                'email' => 'required|email|unique:parameters,email',
                'adresse' => 'required|string|max:255',
                'postal_code' => 'required|string|max:50',
                'phone' => 'required|string|max:50', 
                'country' => 'required|string|max:50',
                'city' => 'required|string|max:50',           
                'company_name' => 'required|string|max:255',
                //'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                
                // ------------- vehicule ------------------
                'licence_plate' => 'required|string|max:50|unique:vehicules,licence_plate',
                'chassis_number' => 'required|string|max:50',
                'odometer_reading' => 'required|string|max:50',
                'year_registration' => 'required|string|max:4',
                'fuel_type' => 'required|string|max:50',
                'vehicle_type' => 'required|string|max:50',
                'gear_box' => 'required|string|max:50',
                'engine_size' => 'required|string|max:50',    
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

                // ------------- appointment ------------------
                'selected_date' => 'required|date',
                'selected_time' => 'required|date_format:H:i',
                'notes' => 'required|date_format:H:i',
                //'status' => 'required|in: pending, confirmed, cancelled',
            ]);

            // customer already exists into db ?
            if(!$user->customer){

                // Calling the CustomerController and its store() method
                $customerController = App::make(CustomerController::class);

                // CustomerController result recovery
                $customerResponse = $customerController->store($request);

                // Retrieve the Customer created from the response (let's assume that CustomerController returns the model in “customer”)
                $customer = $customerResponse->getData()->customer ?? null;

                if(!$customer){
                    return response()->json([
                        'message' => 'Unable to create customer'
                    ], 422);
                }
            }

            // vehicle already exists into db ?
            $exists_car = $customer->vehicules()->where('license_plate', $request->license_plate)->first();             

            if(!$exists_car){

                // Calling the CustomerController and its store() method
                $vehiculeController = App::make(VehiculeController::class);

                // CustomerController result recovery
                $vehiculeResponse = $vehiculeController->store($request);

                // Retrieve the Customer created from the response (let's assume that CustomerController returns the model in “customer”)
                $vehicule = $vehiculeResponse->getData()->vehicule ?? null;

                if(!$vehicule){
                    return response()->json([
                        'message' => 'Unable to create vehicle.'
                    ], 422);
                }                        
            }

            $appointment = Appointment::where('vehicule_id', $vehicule->id)
                            ->where('appointment_date', $request->appointment_date)
                            ->where('start_time', $request->start_time)
                            ->first();

            if(!$appointment){

                //storing appointment into db
                $appointment = $vehicule->appointments()->create([
                    'customer_id' => $customer->id,
                    'vehicule_id' => $vehicule->id,
                    'selected_date' => $request->selected_date,
                    'selected_time' => $request->start_time,
                    'notes' => $request->end_time,
                ]);

                return response()->json([
                    'appointment' => $appointment
                ], 201);

            }else{

                // Already done
                return response()->json([
                    'message' => 'Appointments already booked.'
                ], 422);
            }
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){

        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin role ?
            if ($user->hasAnyRole(['admin', 'client'])) {

                // Simple customer case: assumes that a customer is linked to a Customer model
                $customer_id = $user->customer?->id;
                $appointment = Appointment::with(['customer', 'vehicule'])
                                    ->where('customer_id', $customer_id)
                                    ->where('id', $id)
                                    ->get();

                if(!$appointment){

                    return response()->json([
                        'message' => 'This appointment does not exist or has already been deleted.'
                    ], 404);
                }
                
                return response()->json([
                    'appointment' => $appointment
                ], 200);
            }
        }else{

            // unauthorized action
            return response()->json([
                'message' => 'you should first loggin.',
            ], 404);
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
            if (!$user->hasRole('client') || $user->id !== $user->customer?->id) {
                // unauthorized action
                return response()->json([
                    'message' => 'Access denied.'
                ], 403);
            }

            $appointment = Appointment::find($id);

            // validate datas infos
            $validate = $request->validate([
                // ------------- customer ------------------
                'last_name' => 'required|string|max:255|unique:customers,last_name',
                'gender' => 'required|string|max:1',
                'email' => 'required|email|unique:parameters,email',
                'adresse' => 'required|string|max:255',
                'postal_code' => 'required|string|max:50',
                'phone' => 'required|string|max:50', 
                'country' => 'required|string|max:50',
                'city' => 'required|string|max:50',           
                'company_name' => 'required|string|max:255',
                //'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                
                // ------------- vehicule ------------------
                'licence_plate' => 'required|string|max:50|unique:vehicules,licence_plate',
                'chassis_number' => 'required|string|max:50',
                'odometer_reading' => 'required|string|max:50',
                'year_registration' => 'required|string|max:4',
                'fuel_type' => 'required|string|max:50',
                'vehicle_type' => 'required|string|max:50',
                'gear_box' => 'required|string|max:50',
                'engine_size' => 'required|string|max:50',    
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',

                // ------------- appointment ------------------
                'selected_date' => 'required|date',
                'selected_time' => 'required|date_format:H:i',
                'notes' => 'required|date_format:H:i',
                //'status' => 'required|in: pending, confirmed, cancelled',
            ]);

            // customer exists into db ?
            $customer = $appointment->customer;

            if(!$customer){

                return response()->json([
                    'message' => 'No customer associated with this appointment.',
                ], 404);                
            }

            // updating customer datas
            $customer->update($validate);

            // vehicle exists into db ?
            $vehicule = $appointment->vehicule;

            if(!$vehicule){

                return response()->json([
                    'message' => 'No vehicle associated with this appointment.',
                ], 404);                        
            }

            // updating vehicle datas
            $vehicule->update($validate);

            // updating appointment
            $appointment->update($validate);

            return response()->json([
                'message' => 'Your appointment has successfully being updated.'
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

            $appointment = Appointment::find($id);

            $appointment->delete();

            return response()->json([
                'message' => 'Successfully deleted.'
            ], 201);
        }
    }
}
