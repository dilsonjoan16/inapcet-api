<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Fecha de Creacion</th>
            <th>Fecha de Modificacion</th>
            <th>Fecha de eliminacion</th>
            <th>Fecha de Restauracion</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usuarios as $d)
        <tr>
            <td>{{$d->id}}</td>
            <td>{{$d->name}}</td>
            <td>{{$d->email}}</td>
            <td>{{$d->state == 1 ? "Activo" : "Inactivo"}}</td>
            <td>{{$d->created_at}}</td>
            <td>{{$d->updated_at}}</td>
            <td>{{$d->deleted_at == null ? "Sin eliminar" : $d->deleted_at}}</td>
            <td>{{$d->restored_at == null ? "Sin restaurar" : $d->restored_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
