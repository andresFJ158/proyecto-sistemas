<?php

namespace Database\Seeders;

use App\Models\RequestType;
use App\Models\Resource;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'ADMIN'], ['label' => 'Administrativo']);
        $studentRole = Role::firstOrCreate(['name' => 'STUDENT'], ['label' => 'Estudiante']);

        $admin = User::updateOrCreate(['email' => 'admin@campus.test'], [
            'role_id' => $adminRole->id,
            'name' => 'Ana Administradora',
            'password' => Hash::make('password'),
        ]);
        $student = User::updateOrCreate(['email' => 'estudiante@campus.test'], [
            'role_id' => $studentRole->id,
            'name' => 'Carlos Estudiante',
            'password' => Hash::make('password'),
        ]);

        foreach ([
            'Tecnología' => 'Soporte de software, redes y equipos informáticos.',
            'Infraestructura' => 'Aulas, edificios, instalaciones y servicios básicos.',
            'Equipamiento' => 'Mobiliario y equipos de uso académico.',
            'Mantenimiento' => 'Reparaciones y mantenimiento preventivo.',
            'Otros' => 'Solicitudes institucionales no clasificadas.',
        ] as $name => $description) {
            RequestType::firstOrCreate(['name' => $name], compact('description'));
        }

        Resource::firstOrCreate(['code' => 'PROY-001'], ['name' => 'Proyector Epson', 'category' => 'Tecnología', 'status' => 'DISPONIBLE', 'location' => 'Almacén central']);
        Resource::firstOrCreate(['code' => 'LAP-014'], ['name' => 'Laptop Lenovo', 'category' => 'Tecnología', 'status' => 'ASIGNADO', 'location' => 'Soporte TI']);
        Resource::firstOrCreate(['code' => 'SIL-120'], ['name' => 'Silla ergonómica', 'category' => 'Mobiliario', 'status' => 'MANTENIMIENTO', 'location' => 'Taller']);

        if (ServiceRequest::count() === 0) {
            $types = RequestType::pluck('id', 'name');
            $rows = [
                ['CC-DEMO-001', 'Internet inestable en laboratorio', 'La conexión se interrumpe durante las clases prácticas.', 'ALTA', 'EN_PROCESO', 'Laboratorio 2'],
                ['CC-DEMO-002', 'Luminaria dañada', 'Una luminaria del pasillo principal no enciende.', 'MEDIA', 'ASIGNADA', 'Bloque A'],
                ['CC-DEMO-003', 'Proyector sin imagen', 'El proyector enciende pero no muestra señal HDMI.', 'ALTA', 'PENDIENTE', 'Aula 204'],
                ['CC-DEMO-004', 'Reparación de pupitre', 'El soporte lateral del pupitre está flojo y requiere ajuste.', 'BAJA', 'RESUELTA', 'Aula 105'],
                ['CC-DEMO-005', 'Cambio de teclado', 'Varias teclas no responden en el equipo de biblioteca.', 'MEDIA', 'CERRADA', 'Biblioteca'],
            ];
            foreach ($rows as [$code, $title, $description, $priority, $status, $location]) {
                $item = ServiceRequest::create([
                    'tracking_code' => $code,
                    'user_id' => $student->id,
                    'request_type_id' => $types[$title === 'Luminaria dañada' || $title === 'Reparación de pupitre' ? 'Infraestructura' : 'Tecnología'],
                    'assigned_to' => $status === 'PENDIENTE' ? null : $admin->id,
                    'title' => $title,
                    'description' => $description,
                    'priority' => $priority,
                    'status' => $status,
                    'location' => $location,
                    'closed_at' => $status === 'CERRADA' ? now() : null,
                ]);
                $item->statusHistory()->create(['user_id' => $student->id, 'old_status' => null, 'new_status' => 'PENDIENTE', 'comment' => 'Solicitud registrada.']);
                if ($status !== 'PENDIENTE') {
                    $item->statusHistory()->create(['user_id' => $admin->id, 'old_status' => 'PENDIENTE', 'new_status' => $status, 'comment' => 'Avance de demostración.']);
                }
            }
            ServiceRequest::first()->comments()->create(['user_id' => $admin->id, 'body' => 'Estamos revisando el punto de red con el equipo de soporte.']);
        }
    }
}
