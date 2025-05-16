<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset password</title>
    </head>

    <body>
        <h1 style="display:inline-block; width: 60%; background-color: beige; padding: 1.5% 0; margin: 2% 15%; text-align:center;">Verify your password reset code</h1>

        <p style="display: inline-block; width: 60%; margin: 2% 15%; text-align: justify; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5em; font-size: 1.4em;">
            Hello Miss/Mrs <strong>{{ ucwords($user->name) }}</strong>,<br/>
            Your password reset code is: <strong>{{ $verificationCode }}</strong>
        </p>

        <p  style="display: inline-block; width: 60%; margin: 2% 15%; text-align: justify; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5em; font-size: 1.4em;">
            Please copy/paste the given code to reinitialize your password.
        </p>
    </body>
</html>