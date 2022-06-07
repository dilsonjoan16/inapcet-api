<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        .tabla{
            background-color: rgb(255, 212, 168);
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
<table class="tabla">

        <tr>
            <th>Id: </th>
            <td>{{$proyecto->id}}</td>

        </tr>
        <tr>
            <th>Nombre: </th>
            <td>{{$proyecto->name}}</td>

        </tr>
        <tr>
            <th>Descripcion: </th>
            <td>{{$proyecto->description}}</td>

        </tr>
        <tr>
            <th>Duracion estimada: </th>
            <td>{{$proyecto->duration == null || $proyecto->duration == 0 ? "Sin estimar" : "$proyecto->duration Semanas"}}</td>

        </tr>
        <tr>
            <th>Presupuesto estimado: </th>
            <td>{{$proyecto->estimated == null || $proyecto->estimated == 0 ? "Sin estimar" : "Bs. $proyecto->estimated"}}</td>
        </tr>
        <tr>
            <th>Etapa del proyecto: </th>
            <td>{{$proyecto->stage}}</td>
        </tr>
        <tr>
            <th>Estado: </th>
            <td>{{$proyecto->state == 1 ? "Activo" : "Inactivo"}}</td>

        </tr>
        <tr>
            <th>Fecha de Creacion: </th>
            <td>{{$proyecto->created_at}}</td>

        </tr>
        <tr>
            <th>Fecha de Modificacion: </th>
            <td>{{$proyecto->updated_at}}</td>

        </tr>
        <tr>
            <th>Fecha de eliminacion: </th>
            <td>{{$proyecto->deleted_at == null ? "Sin eliminar" : $proyecto->deleted_at}}</td>

        </tr>
        <tr>
            <th>Fecha de Restauracion: </th>
            <td>{{$proyecto->restored_at == null ? "Sin restaurar" : $proyecto->restored_at}}</td>

        </tr>
</table>
</body>
</html>
