<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Customer;
use App\Mail\VerifyEmail;
use App\Models\User;

class CustomerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    use HasRoles;

    public function index() {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin role ?
            if ($user->hasRole('admin')) {

                // list all customers
                $customers = Customer::with('user')->get();

                return response()->json($customers);

            }else{

                // unauthorizwd action (! admin)
                return response()->json([
                    'status' => 401,
                    'message' => 'Not authorized action.'
                ]);
            }
        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'you should first loggin.',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // initialize vehicles images path
        $filePath = ""; 
        
        // validate datas customer infos
        $request->validate([
            // ------------- customer ------------------
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:1',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:8|confirmed',
            'adresse' => 'required|string|max:255',
            'postal_code' => 'required|string|max:50',
            'phone' => 'required|string|max:50', 
            'country' => 'required|string|max:50',
            'city' => 'required|string|max:50',           
            'company_name' => 'sometimes|string|max:255',
            'image_principale' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',            
        ]);

        // save first user credentials
        $account = User::where('email', $request->email)->first();

        if(!$account){

            $password = Hash::make($request->password, ['rounds' => 12]);
            $verify_otp = Str::random(10);
            $password_reset_code = Str::random(6);
            $name = ucwords($request->first_name) . ' ' . ucwords($request->last_name);

            $account = [
                'name' => $name,
                'email' => $request->email,
                'password' => $password,
                'password_reset_code' => $password_reset_code,
                'verify_otp' => $verify_otp,
            ];

            // create user account
            $user = User::create($account);

            // assigned "client" role
            $user->assignRole('client');

            // create token
            $token = $user->createToken($name);            

            // some specific variables
            $action = 'emails.verify_email';
            $subject = 'verify email';       // form to show

            // send email verification link
            Mail::to($user->email)->send(new VerifyEmail($user, $verify_otp, $action, $subject));       
        
            // --------------------- Customer registration logic ---------------
           
            // customer already exists ?
            $client = Customer::where('email', $request->email)->first();

            if(!$client){

                // manage images
                if($request->hasFile('image_principale')){

                    // get image details
                    $file = $request->file('image_principale');
                    $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                    // path where to store image "storage/app/public/images/customers"
                    $filePath = $file->storeAs('images/customers', $fileName, 'public');
                }

                // storing customer in to db
                $customer = $user->customer()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'email' => $request->email,
                    'adresse' => $request->adresse,
                    'postal_code' => $request->postal_code,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'city' => $request->city,
                    'profession' => $request->profession,
                    'company_name' => $request->company_name,
                    'url_photo' => $filePath,
                ]);

                return response()->json([
                    'customer' => $customer,
                    'message' => 'Dear '. ucwords($request->first_name) . '' . ucwords($request->last_name) .', Please check your inbox to activate your account.',
                ], 201);
                
            }else{

                // customer already exists
                return response()->json([
                    'message' => ucwords($request->first_name) . '' . ucwords($request->last_name) . ' already exists',
                ], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // current user is connected ?
        if(Auth::check()){

            // get user connected datas 
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // get customer datas
            $customer = Customer::with('user')->findOrFail($id);

            // check if it's admin or manager role ?
            if (!$user->hasAnyRole(['admin', 'client'])  || $user->id !== $customer->user_id) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            return response()->json($customer);
        }else{

            // unauthorized action
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized action.',
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

            $customer = Customer::findOrFail($id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'client']) && $user->id === $customer->user_id) {

                // initialize customer image path
                $filePath = "";                 
                
                // validate datas customer infos
                $request->validate([
                    // ------------- customer ------------------
                    'last_name' => 'required|string|max:255',
                    'gender' => 'required|string|max:1',
                    'email' => 'required|email|unique:customers,email',
                    'adresse' => 'required|string|max:255',
                    'postal_code' => 'required|string|max:50',
                    'phone' => 'required|string|max:50', 
                    'country' => 'required|string|max:50',
                    'city' => 'required|string|max:50',           
                    'company_name' => 'required|string|max:255',            
                ]);

                // get customer datas to know if there's old image
                $customer = Customer::find($id);

                // manage images
                if($request->hasFile('image_principale')){

                    if($customer){
                        // delete the old image in path: /public/images/customers
                        Storage::disk('public')->delete($customer->url_photo);
                    }            

                    // get image details
                    $file = $request->file('image_principale');
                    $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                    // path where to store image "storage/app/public/images/customers"
                    $filePath = $file->storeAs('images/customers', $fileName, 'public');
                }

                // storing customer in to db
                $customer = Customer::update([
                    'user_id' => $request->user_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'email' => $request->email,
                    'adresse' => $request->adresse,
                    'postal_code' => $request->postal_code,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'city' => $request->city,
                    'profession' => $request->profession,
                    'company_name' => $request->company_name,
                    'url_photo' => $filePath,
                ]);

                return response()->json([
                    'status' => 201,
                    'customer' => $customer,
                ]);
            }else{
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }
        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should loggin first.',
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
            if ($user->hasRole('admin')) {

                $customer = Customer::findOrFail($id);

                // first get user which customer belongs to
                $user = $customer->user;

                // deleted all vehicules belongs to customer
                foreach ($customer->vehicules as $vehicule) {

                    // delete images physically if exists
                    if ($vehicule->url_pictures && Storage::disk('public')->exists($vehicule->url_pictures)) {
                        Storage::disk('public')->delete($vehicule->url_pictures);
                    }

                    // delete vehicule
                    $vehicule->delete();
                }

                // then delete the customer
                $customer->delete();

                // at last delete the user
                $user->delete();

                return response()->json([
                    'status' => 201,
                    'message' => 'Customer has successfully being deleted.'
                ]);
            }
        }
    }
}
