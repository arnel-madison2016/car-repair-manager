<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resend verification email</title>
    </head>

    <body>
        <h1 style="display:inline-block; width: 60%; background-color: beige; padding: 1.5% 0;">Notice</h1>

        <p style="display: inline-block; width: 60%; text-align: justify; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5em; font-size: 1.4em;">
            Please click the following link to resend verification email:
            <a href="{{ route('resend.verification.email', ['id' => $user->id]) }}">Resend</a>
        </p>
    </body>
</html>