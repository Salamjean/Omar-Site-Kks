<!DOCTYPE html>
<html>
<head>
    <title>OMAR - Confirmation d'enregistrement</title>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <img src="{{ $logoUrl }}" alt="Logo OMAR" width="150">
            </td>
        </tr>
        <tr>
            <td>
                <h1>Création du compte du personnel</h1>
                <p>Votre compte a été créé avec succès chez omar.</p>
                <p>Cliquez sur le bouton ci-dessous pour valider votre compte.</p>
                <p>Saisissez le code <strong>{{ $code }}</strong> dans le formulaire qui apparaîtra.</p>
                <p><a href="{{ url('/validate-vendor-account/' . $email) }}" style="background-color:#4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer;">Valider mon compte</a></p>
                <p>Merci de travailler pour OMAR.</p>
            </td>
        </tr>
    </table>
</body>
</html>