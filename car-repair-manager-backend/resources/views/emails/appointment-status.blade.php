<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Request appointment</title>
    </head>

    <body>

        @if ($appointment->status === 'confirmed')

            <h2  style="display:inline-block; width:65%; margin: 2% 15%; padding:1% 0; text-align: center; background-color: beige;">Hello Miss/Mrs <strong>{{ ucwords($appointment->customer->first_name) .' '. ucwords($appointment->customer->last_name) }} ,</strong></h2>

            <p style="display:inline-block; width:65%; margin: 2% 15%; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif; text-align: justify; font-size: 1.4em; line-height: 1.5em;">
                We are pleased to inform you that your request for an appointment on <strong>{{ \Carbon\Carbon::parse($appointment->selected_date)->translatedFormat('F d Y') }}</strong> at <strong>{{ $appointment->selected_time }}</strong>
                for the diagnosis of your vehicle <strong> {{ $appointment->vehicule->car_model->brand->name }} {{ $appointment->vehicule->car_model->name }}</strong>, registration number <strong>{{ strtoupper($appointment->vehicule->license_plate) }}</strong>, has been confirmed.
                <br/><br/>
                Thank you for continuing to place your trust in us.<br/><strong>Team Management.</strong>
            </p>
       
        @else

            <h2  style="display:inline-block; width:65%; margin: 2% 15%; padding:1% 0; text-align: center; background-color: beige;">Hello Miss/Mrs <strong>{{ ucwords($appointment->customer->first_name) .' '. ucwords($appointment->customer->last_name) }} ,</strong></h2>

            <p style="display:inline-block; width:65%; margin: 2% 15%; font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif; text-align: justify; font-size: 1.4em; line-height: 1.5em;">
                Due to the maintenance of our technical facilities, we regret to inform you that your request for an appointment on <strong>{{ \Carbon\Carbon::parse($appointment->selected_date)->translatedFormat('F d Y') }}</strong> at <strong>{{ $appointment->selected_time }}</strong>
                for the diagnosis of your vehicle <strong> {{ $appointment->vehicule->car_model->brand->name }} {{ $appointment->vehicule->car_model->name }}</strong>, registration number <strong>{{ strtoupper($appointment->vehicule->license_plate) }}</strong>, cannot be processed.
                <br/><br/>
                We apologise for any inconvenience this may have caused and thank you for the trust you have placed in us.
                <br/><strong>Team Management.</strong>
            </p>
        @endif
        
    </body>
</html>