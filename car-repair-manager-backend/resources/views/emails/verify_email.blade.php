<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Email Address</title>
    </head>
    
    <body>
        <h1 style="display:inline-block; width: 60%; background-color: beige; padding: 1.5% 0;">Verify Your Email Address</h1>
       
        <p style="display: inline-block; width: 60%; text-align: justify; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5em; font-size: 1.4em;">
            Hello <strong>{{ ucwords($user->name) }}</strong>,<br/><br/>
            Your verification code is: <strong>{{ $verificationCode }}</strong>
        </p>

        <p style="display: inline-block; width: 60%; text-align: justify; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5em; font-size: 1.4em;">
            Please click the following link to verify your email address:
            <br/>
            <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => $verificationCode]) }}">Verify Email</a>
        </p>
    </body>
</html>
