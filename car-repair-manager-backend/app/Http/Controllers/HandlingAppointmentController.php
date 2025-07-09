<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

use App\Mail\AppointmentNotification;
use App\Models\Appointment;

class HandlingAppointmentController extends Controller {

    public function handleAppointment(Request $request, $id) {

        // which action to perform action: 0 = cancelled    -   1 = confirmed
        $status = $request->action;
        $action = '';

        $appointment = Appointment::find($id);

        if($status === '0'){

            $appointment->status = 'cancelled';
            $action = 'emails.appointment_cancelled';
        }else{
            $appointment->status = 'confirmed';
            $action = 'emails.appointment_confirmation';
        }

        // updating appointment status
        $appointment->save();

        // get customer email to send notification
        $email = $appointment->customer()->email;

        // sending confirmed or cancelled notification to customer
        Mail::to($email)->send(new AppointmentNotification($appointment, $action));
        
        return response()->json([
            'appointment' => $appointment,
            'message' => 'Email notification has successfully being send !',
        ], 201);
    }
}
