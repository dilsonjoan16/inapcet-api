<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aviso de Registro</title>
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
            Notificacion de registro de personal en {{$datos[2]["name"]}} - I.N.A.P.C.E.T
        </h2>
        <br>
        <p>
            Estimad@ Gerente. Se le informa que hemos recibido, la notificacion sobre un nuevo registro de personal en su departamento, por ende, se le solicita la verificacion del nuevo usuario y su activacion inmediata en caso de resultar correcta.
            <i> La activacion se lleva a cabo en el apartado <strong>Papelera de Usuarios,</strong> alli solo debe restaurar al usuario recien registrado</i>
            <br>
            <br>
            <strong>
                En caso de que el usuario no pertenezca al Instituto y/o no deba acceder al sistema, se le solicita ignorar la solicitud y comunicar dicha situacion al personal del Area de Sistemas, para su revision y correccion inmediata, evitando un acceso indeseado al Sistema.
            </strong>
        </p>
        <br>
        <hr>
        <br>
        <h3>
            Usuario Registrado:
        </h3>
        <ul>
            <li><strong>Nombre: </strong>{{$datos[0]}}</li>
            <li><strong>Email: </strong>{{$datos[1]}}</li>
            <li><strong>Rol: </strong>Profesional</li>
            <li><strong>Estado: </strong>Inactivo</li>
            <li><strong>Departamento: </strong>{{$datos[2]["name"]}}</li>
            <li><strong>Fecha de registro: </strong>{{$datos[3]}}</li>
        </ul>
        <br>
        <hr>
        <br>
            <i>Se agradece prestar toda la colaboracion posible y cumplir con las obligaciones de su Cargo.</i>
        <br>
        <br>

        <strong style="margin: 0px 30%">Sistema de Gestion y Control - I.N.A.P.C.E.T</strong>
    </section>
</body>
</html>

