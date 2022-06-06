<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperacion de Password</title>
    <style>
        body{
            font-family: 'Nunito', sans-serif;
        }
    </style>
    <style>
        .titulo{
            background-color: rgba(253, 17, 17, 0.884);
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            color: white;
            padding: 25px;
        }
    </style>
    <style>
        .seccion{
            background-color: rgb(255, 252, 249);
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
            color: black !important;
            padding: 15px;
        }
    </style>
</head>
<body>

        <h1 class="titulo">
            Instituto Autonomo para el poder Comunal del Estado Tachira - I.N.A.P.C.E.T
        </h1>


    <section class="seccion">
        <h2>
            Correo de restauracion de Constraseña del personal - I.N.A.P.C.E.T
        </h2>
        <br>
        <p>
            Estimad@ profesional <strong><i>{{$datos[1]}}</i></strong>. Se le informa que hemos recibido por parte de usted, una solicitud para el restablecimiento de su clave en el Sistema.
            El portal le generara una password provicional totalmente aleatoria, cumpliendo con los altos estandares de seguridad manejados por nosotros; esta misma puede ser modificada a traves del portal web si usted lo desea.
            <strong>
                En caso de ser falsa esta solicitud, le solicitamos comunicarse de inmediato con su Gerente o en su defecto con el personal del Area de Sistemas, para su revision y correccion inmediata.
            </strong>
        </p>
        <br>
        <hr>
        <br>
        <strong>
            Constraseña generada:</i> {{$datos[0]}}
        </strong>
        <br>
        <hr>
        <br>
            <i>Para el proximo ingreso al sistema, debera hacer uso del nuevo recurso otorgado.</i>
        <br>
        <br>

        <strong style="margin: 0px 30%">Sistema de Gestion y Control - I.N.A.P.C.E.T</strong>
    </section>
</body>
</html>

