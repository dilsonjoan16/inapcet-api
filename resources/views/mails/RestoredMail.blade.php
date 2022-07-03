<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de Restauracion</title>
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
            Notificacion de restauracion de personal - I.N.A.P.C.E.T
        </h2>
        <br>
        <p>
            Estimad@ {{$datos[0]}}. Se le informa que su estado en el Sistema fue modificado, pasando a ser nuevamente un <strong>Usuario Activo</strong>.
            <i> La activacion se llevo a cabo por: <strong>{{$datos[3]}}</strong></i>
            <br>
            <br>
            <strong>
                Se le agradece cumplir con sus obligaciones correctamente y nos complace darle la Bienvenida nuevamente al Sistema
            </strong>
        </p>
        <br>
        <hr>
        <br>
        <h3>
            Usuario Activado:
        </h3>
        <ul>
            <li><strong>Nombre: </strong>{{$datos[0]}}</li>
            <li><strong>Email: </strong>{{$datos[1]}}</li>
            {{-- <li><strong>Fecha de restauracion: </strong>{{$datos[2]}}</li> --}}
        </ul>
        <br>
        <hr>
        <br>
            <i>Bienvenido y no olvide cumplir con las obligaciones de su Cargo.</i>
        <br>
        <br>

        <strong style="margin: 0px 30%">Sistema de Gestion y Control - I.N.A.P.C.E.T</strong>
    </section>
</body>
</html>

