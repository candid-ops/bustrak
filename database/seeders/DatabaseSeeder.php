<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole    = Role::create(['name' => 'admin']);
        $driverRole   = Role::create(['name' => 'driver']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create admin user
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@bustrak.com',
            'phone'    => '0700000000',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create a sample customer
        $customer = User::create([
            'name'     => 'John Doe',
            'email'    => 'customer@bustrak.com',
            'phone'    => '0711111111',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');

        // Create sample buses
        $bus1 = \App\Models\Bus::create([
            'plate_number' => 'KAA 123A',
            'name'         => 'Express 1',
            'capacity'     => 45,
            'type'         => 'intercity',
            'status'       => 'active',
        ]);

        $bus2 = \App\Models\Bus::create([
            'plate_number' => 'KBB 456B',
            'name'         => 'City Hopper',
            'capacity'     => 30,
            'type'         => 'city',
            'status'       => 'active',
        ]);

        // Create a driver user
        $driverUser = User::create([
            'name'     => 'James Mwangi',
            'email'    => 'driver@bustrak.com',
            'phone'    => '0722222222',
            'password' => Hash::make('password'),
        ]);
        $driverUser->assignRole('driver');

        \App\Models\Driver::create([
            'user_id'        => $driverUser->id,
            'bus_id'         => $bus1->id,
            'licence_number' => 'DL-12345',
            'status'         => 'active',
        ]);

        // Create sample routes
        $route1 = \App\Models\Route::create([
            'origin'      => 'Nairobi',
            'destination' => 'Mombasa',
            'stops'       => ['Mtito Andei', 'Voi'],
            'distance_km' => 480,
            'fare'        => 1200,
            'type'        => 'intercity',
            'is_active'   => true,
        ]);

        $route2 = \App\Models\Route::create([
            'origin'      => 'Nairobi CBD',
            'destination' => 'Westlands',
            'stops'       => ['University Way', 'Kencom'],
            'distance_km' => 5,
            'fare'        => 50,
            'type'        => 'city',
            'is_active'   => true,
        ]);

        // Create sample schedules
        \App\Models\Schedule::create([
            'route_id'       => $route1->id,
            'bus_id'         => $bus1->id,
            'driver_id'      => 1,
            'departure_time' => '07:00:00',
            'days'           => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'status'         => 'active',
        ]);

        \App\Models\Schedule::create([
            'route_id'       => $route2->id,
            'bus_id'         => $bus2->id,
            'driver_id'      => 1,
            'departure_time' => '08:00:00',
            'days'           => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'status'         => 'active',
        ]);
    }
}