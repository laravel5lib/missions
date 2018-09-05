<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NewPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'view_influencer_applications']);
        Permission::create(['name' => 'edit_influencer_applications']);
        Permission::create(['name' => 'add_influencer_applications']);
        Permission::create(['name' => 'delete_influencer_applications']);

        Permission::create(['name' => 'view_media_credentials']);
        Permission::create(['name' => 'edit_media_credentials']);
        Permission::create(['name' => 'add_media_credentials']);
        Permission::create(['name' => 'delete_media_credentials']);

        Permission::create(['name' => 'view_medical_credentials']);
        Permission::create(['name' => 'add_medical_credentials']);
        Permission::create(['name' => 'edit_medical_credentials']);
        Permission::create(['name' => 'delete_medical_credentials']);

        Permission::create(['name' => 'view_airport_preferences']);
        Permission::create(['name' => 'add_airport_preferences']);
        Permission::create(['name' => 'edit_airport_preferences']);
        Permission::create(['name' => 'delete_airport_preferences']);

        Permission::create(['name' => 'view_arrival_designations']);
        Permission::create(['name' => 'add_arrival_designations']);
        Permission::create(['name' => 'edit_arrival_designations']);
        Permission::create(['name' => 'delete_arrival_designations']);

        Permission::create(['name' => 'view_minor_releases']);
        Permission::create(['name' => 'add_minor_releases']);
        Permission::create(['name' => 'edit_minor_releases']);
        Permission::create(['name' => 'delete_minor_releases']);


        $admin = Role::where('name', 'admin')->firstOrFail();
        $admin->givePermissionTo([
            'view_influencer_applications',
            'edit_influencer_applications',
            'add_influencer_applications',
            'delete_influencer_applications',
            'view_media_credentials',
            'edit_media_credentials',
            'add_media_credentials',
            'delete_media_credentials',
            'view_medical_credentials',
            'add_medical_credentials',
            'edit_medical_credentials',
            'delete_medical_credentials',
            'view_airport_preferences',
            'add_airport_preferences',
            'edit_airport_preferences',
            'delete_airport_preferences',
            'view_arrival_designations',
            'add_arrival_designations',
            'edit_arrival_designations',
            'delete_arrival_designations',
            'view_minor_releases',
            'add_minor_releases',
            'edit_minor_releases',
            'delete_minor_releases'
        ]);


        // create roles and assign existing permissions
        $superAdmin = Role::where('name', 'super_admin')->firstOrFail();
        $superAdmin->givePermissionTo([
            'view_influencer_applications',
            'edit_influencer_applications',
            'add_influencer_applications',
            'delete_influencer_applications',
            'view_media_credentials',
            'edit_media_credentials',
            'add_media_credentials',
            'delete_media_credentials',
            'view_medical_credentials',
            'add_medical_credentials',
            'edit_medical_credentials',
            'delete_medical_credentials',
            'view_airport_preferences',
            'add_airport_preferences',
            'edit_airport_preferences',
            'delete_airport_preferences',
            'view_arrival_designations',
            'add_arrival_designations',
            'edit_arrival_designations',
            'delete_arrival_designations',
            'view_minor_releases',
            'add_minor_releases',
            'edit_minor_releases',
            'delete_minor_releases'
        ]);

        $travelDocumentModerator = Role::where('name', 'travel_documents_moderator')->firstOrFail();
        $travelDocumentModerator->givePermissionTo([
            'view_influencer_applications',
            'edit_influencer_applications',
            'add_influencer_applications',
            'delete_influencer_applications',
            'view_media_credentials',
            'edit_media_credentials',
            'add_media_credentials',
            'delete_media_credentials',
            'view_medical_credentials',
            'add_medical_credentials',
            'edit_medical_credentials',
            'delete_medical_credentials',
            'view_airport_preferences',
            'add_airport_preferences',
            'edit_airport_preferences',
            'delete_airport_preferences',
            'view_arrival_designations',
            'add_arrival_designations',
            'edit_arrival_designations',
            'delete_arrival_designations',
            'view_minor_releases',
            'add_minor_releases',
            'edit_minor_releases',
            'delete_minor_releases'
        ]);
    }
}
