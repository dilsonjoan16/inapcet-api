<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anuncio</title>
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

        <h1>
            {{$datos[1]}} - I.N.A.P.C.E.T
        </h1>
        <br>
        <h4>
           ANUNCIANTE: <br>
           <ul>
            <li>
                Nombre: {{$datos[0]}}
            </li>
            <li>
                Correo electronico: {{$datos[3]}}
            </li>
           </ul>
        </h4>
        <br>
        <p>
            <i>
                {{$datos[2]}}
            </i>
        </p>
        <br>
        <hr>
        <br>
        <h3>
            Este mensaje fue generado desde el Sistema, se agradece confirmar la recepcion del mismo al anunciante, a traves, del Sistema utilizando el apartado de "ANUNCIAR" o en su defecto comunicandose directamente al correo del anunciante.
        </h3>
        <br>
        <i>El correo del anunciante se encuentra junto al nombre del mismo al inicio de este mensaje</i>.
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

