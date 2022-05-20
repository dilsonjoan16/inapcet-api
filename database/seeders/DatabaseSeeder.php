<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Departamento::create([
            "name" => "Coordinacion de Tecnologia y Sistemas",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Gerencia de Proyectos",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Salas Tecnicas",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Direccion Ejecutiva",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Recursos Humanos",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Auditoria Interna",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Formacion y Desarrollo",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Presidencia",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Administracion",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Atencion al Cliente",
            "state" => 1,
        ]);
        Departamento::create([
            "name" => "Asistencia Juridica",
            "state" => 1,
        ]);
        Role::create([
            "name" => "Administrador",
            "state" => 1,
        ]);
        Role::create([
            "name" => "Invitado",
            "state" => 1,
        ]);
        Role::create([
            "name" => "Gerente",
            "state" => 1,
        ]);
        Role::create([
            "name" => "Coordinador",
            "state" => 1,
        ]);
        Role::create([
            "name" => "Auditor",
            "state" => 1,
        ]);
    }
}
