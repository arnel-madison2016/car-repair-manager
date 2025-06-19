<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Parameter;
use App\Models\User;

use Spatie\Permission\Traits\HasRoles;
class ParameterController extends Controller {
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
            if (!$user->hasRole('admin')) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);

            }

            // list parameters
            $parameters = Parameter::all();

            return response()->json([
                'status' => 200,
                'parameters' => $parameters,
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
    public function store(Request $request){

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

            // validate datas
            $request->validate([
                'name' => 'required|string|max:255|unique:parameters,name',
                'email' => 'required|email|unique:parameters,email',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'phone' => 'required',
                'adresse' => 'required',
                'bank_account' => 'required',
                'url_site' => 'required',
            ]);

            // manage image
            if($request->hasFile('image')){

                // get image details
                $file = $request->file('image');
                $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                // path where to store image "storage/app/public/images/manufacturers"
                $filePath = $file->storeAs('images/logos', $fileName, 'public');
            }

            // storing the datas in to db
            $parameter = Parameter::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'adresse' => $request->adresse,
                'bank_account' => $request->bank_account,
                'url_logo' => $filePath,
                'url_site' => $request->url_site
            ]);

            return [
                'status' => 201,
                'parameter' => $parameter
            ];
        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
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

            // check if it's admin or manager role ?
            if (!$user->hasAnyRole(['admin', 'client'])) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            $parameter = Parameter::findOrFail($id);

            return response()->json($parameter);

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

            // get user connected datas 
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if (!$user->hasAnyRole(['admin', 'client'])) {
                // unauthorized action
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.'
                ]);
            }

            // find the record to update
            $parameter = Parameter::find($id);

            // initialize the path to store images locally
            $filePath = '';        

            // validate datas
            $request->validate([
                'name' => 'required|string|max:255|unique:parameters,name',
                'email' => 'required|email|unique:parameters,email',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'phone' => 'required',
                'adresse' => 'required',
                'bank_account' => 'required',
                'url_site' => 'required',
            ]);       
            
            // manage images
            if($request->hasFile('image')){

                if($parameter){
                    // delete the old image in path: /public/images/logos
                    Storage::disk('public')->delete($parameter->url_logo);
                }            

                // get details from new image
                $file = $request->file('image');
                $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                // path where to store image "storage/app/public/images/logos"
                $filePath = $file->storeAs('images/logos', $fileName, 'public');
            }

            // update record
            $parameter->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'adresse' => $request->adresse,
                'bank_account' => $request->bank_account,
                'url_logo' => $filePath,
                'url_site' => $request->url_site
            ]);

            return response()->json([
                'status' => 200,
                'parameter' => $parameter,
            ]);

        }else{

            // unauthorized action
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized action.',
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
            $parameter = Parameter::find($id);

            if($parameter){
                // delete the old image in path: /public/images/logos
                Storage::disk('public')->delete($parameter->url_logo);

                // delete the record in the database
                $parameter->delete();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Parameter has successfully being deleted.',
            ]);

        }else{

            // unauthorized action
            return response()->json([
                'status' => 404,
                'message' => 'You should first loggin.',
            ]);
        }
    }
}
