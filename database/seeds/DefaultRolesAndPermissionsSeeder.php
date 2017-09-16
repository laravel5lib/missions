<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultRolesAndPermissionsSeeder extends Seeder
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
        Permission::create(['name' => 'access_backend']);
        Permission::create(['name' => 'view_campaigns']);
        Permission::create(['name' => 'add_campaigns']);
        Permission::create(['name' => 'edit_campaigns']);
        Permission::create(['name' => 'delete_campaigns']);
        Permission::create(['name' => 'view_trips']);
        Permission::create(['name' => 'add_trips']);
        Permission::create(['name' => 'edit_trips']);
        Permission::create(['name' => 'delete_trips']);
        Permission::create(['name' => 'view_squads']);
        Permission::create(['name' => 'add_squads']);
        Permission::create(['name' => 'edit_squads']);
        Permission::create(['name' => 'delete_squads']);
        Permission::create(['name' => 'view_rooms']);
        Permission::create(['name' => 'add_rooms']);
        Permission::create(['name' => 'edit_rooms']);
        Permission::create(['name' => 'delete_rooms']);
        Permission::create(['name' => 'view_accommodations']);
        Permission::create(['name' => 'add_accommodations']);
        Permission::create(['name' => 'edit_accommodations']);
        Permission::create(['name' => 'delete_accommodations']);
        Permission::create(['name' => 'view_regions']);
        Permission::create(['name' => 'add_regions']);
        Permission::create(['name' => 'edit_regions']);
        Permission::create(['name' => 'delete_regions']);
        Permission::create(['name' => 'view_transports']);
        Permission::create(['name' => 'add_transports']);
        Permission::create(['name' => 'edit_transports']);
        Permission::create(['name' => 'delete_transports']);
        Permission::create(['name' => 'view_promos']);
        Permission::create(['name' => 'add_promos']);
        Permission::create(['name' => 'edit_promos']);
        Permission::create(['name' => 'delete_promos']);
        Permission::create(['name' => 'view_reservations']);
        Permission::create(['name' => 'add_reservations']);
        Permission::create(['name' => 'edit_reservations']);
        Permission::create(['name' => 'delete_reservations']);
        Permission::create(['name' => 'view_groups']);
        Permission::create(['name' => 'add_groups']);
        Permission::create(['name' => 'edit_groups']);
        Permission::create(['name' => 'delete_groups']);
        Permission::create(['name' => 'view_group_managers']);
        Permission::create(['name' => 'add_group_managers']);
        Permission::create(['name' => 'edit_group_managers']);
        Permission::create(['name' => 'delete_group_managers']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'add_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'view_transactions']);
        Permission::create(['name' => 'add_credit_card_transactions']);
        Permission::create(['name' => 'add_check_transactions']);
        Permission::create(['name' => 'add_credit_transactions']);
        Permission::create(['name' => 'add_cash_transactions']);
        Permission::create(['name' => 'add_transfer_transactions']);
        Permission::create(['name' => 'edit_transactions']);
        Permission::create(['name' => 'delete_transactions']);
        Permission::create(['name' => 'view_donors']);
        Permission::create(['name' => 'add_donors']);
        Permission::create(['name' => 'edit_donors']);
        Permission::create(['name' => 'delete_donors']);
        Permission::create(['name' => 'view_funds']);
        Permission::create(['name' => 'add_funds']);
        Permission::create(['name' => 'edit_funds']);
        Permission::create(['name' => 'delete_funds']);
        Permission::create(['name' => 'view_causes']);
        Permission::create(['name' => 'add_causes']);
        Permission::create(['name' => 'edit_causes']);
        Permission::create(['name' => 'delete_causes']);
        Permission::create(['name' => 'view_projects']);
        Permission::create(['name' => 'add_projects']);
        Permission::create(['name' => 'edit_projects']);
        Permission::create(['name' => 'delete_projects']);
        Permission::create(['name' => 'view_initatives']);
        Permission::create(['name' => 'add_initatives']);
        Permission::create(['name' => 'edit_initatives']);
        Permission::create(['name' => 'delete_initatives']);
        Permission::create(['name' => 'view_passports']);
        Permission::create(['name' => 'add_passports']);
        Permission::create(['name' => 'edit_passports']);
        Permission::create(['name' => 'delete_passports']);
        Permission::create(['name' => 'view_visas']);
        Permission::create(['name' => 'add_visas']);
        Permission::create(['name' => 'edit_visas']);
        Permission::create(['name' => 'delete_visas']);
        Permission::create(['name' => 'view_medical_releases']);
        Permission::create(['name' => 'add_medical_releases']);
        Permission::create(['name' => 'edit_medical_releases']);
        Permission::create(['name' => 'delete_medical_releases']);
        Permission::create(['name' => 'view_credentials']);
        Permission::create(['name' => 'add_credentials']);
        Permission::create(['name' => 'edit_credentials']);
        Permission::create(['name' => 'delete_credentials']);
        Permission::create(['name' => 'view_referrals']);
        Permission::create(['name' => 'add_referrals']);
        Permission::create(['name' => 'edit_referrals']);
        Permission::create(['name' => 'delete_referrals']);
        Permission::create(['name' => 'view_questionnaires']);
        Permission::create(['name' => 'add_questionnaires']);
        Permission::create(['name' => 'edit_questionnaires']);
        Permission::create(['name' => 'delete_questionnaires']);
        Permission::create(['name' => 'view_reports']);
        Permission::create(['name' => 'add_reports']);
        Permission::create(['name' => 'edit_reports']);
        Permission::create(['name' => 'delete_reports']);
        Permission::create(['name' => 'view_costs']);
        Permission::create(['name' => 'add_costs']);
        Permission::create(['name' => 'edit_costs']);
        Permission::create(['name' => 'delete_costs']);
        Permission::create(['name' => 'view_notes']);
        Permission::create(['name' => 'add_notes']);
        Permission::create(['name' => 'edit_notes']);
        Permission::create(['name' => 'delete_notes']);
        Permission::create(['name' => 'view_tasks']);
        Permission::create(['name' => 'add_tasks']);
        Permission::create(['name' => 'edit_tasks']);
        Permission::create(['name' => 'delete_tasks']);
        Permission::create(['name' => 'view_fundraisers']);
        Permission::create(['name' => 'add_fundraisers']);
        Permission::create(['name' => 'edit_fundraisers']);
        Permission::create(['name' => 'delete_fundraisers']);
        Permission::create(['name' => 'give_user_permissions']);
        Permission::create(['name' => 'revoke_user_permissions']);
        Permission::create(['name' => 'assign_user_roles']);
        Permission::create(['name' => 'remove_user_roles']);
        Permission::create(['name' => 'view_squad_members']);
        Permission::create(['name' => 'add_squad_members']);
        Permission::create(['name' => 'edit_squad_members']);
        Permission::create(['name' => 'delete_squad_members']);
        Permission::create(['name' => 'view_occupants']);
        Permission::create(['name' => 'add_occupants']);
        Permission::create(['name' => 'edit_occupants']);
        Permission::create(['name' => 'delete_occupants']);
        Permission::create(['name' => 'view_passengers']);
        Permission::create(['name' => 'add_passengers']);
        Permission::create(['name' => 'edit_passengers']);
        Permission::create(['name' => 'delete_passengers']);
        Permission::create(['name' => 'view_requirements']);
        Permission::create(['name' => 'add_requirements']);
        Permission::create(['name' => 'edit_requirements']);
        Permission::create(['name' => 'delete_requirements']);

        $intern = Role::create(['name' => 'intern']);
        $intern->givePermissionTo([
            'access_backend',
            'view_campaigns',
            'view_trips',
            'view_squads',
            'view_squad_members',
            'add_squad_members',
            'edit_squad_members',
            'delete_squad_members',
            'view_occupants',
            'add_occupants',
            'edit_occupants',
            'delete_occupants',
            'view_passengers',
            'add_passengers',
            'edit_passengers',
            'delete_passengers',
            'view_rooms',
            'view_accommodations',
            'view_regions',
            'view_transports',
            'view_reservations',
            'add_reservations',
            'edit_reservations',
            'view_groups',
            'view_users',
            'edit_users',
            'view_transactions',
            'add_credit_card_transactions',
            'add_donors',
            'edit_donors',
            'view_notes',
            'add_notes',
            'view_reports',
            'add_reports',
            'edit_reports',
            'delete_reports',
            'view_costs',
            'view_requirements',
            'view_fundraisers',
            'edit_fundraisers'
        ]);

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo(array_merge($intern->permissions->pluck('name')->toArray(), [
            'edit_trips',
            'add_squads',
            'edit_squads',
            'delete_squads',
            'add_rooms',
            'edit_rooms',
            'delete_rooms',
            'add_transports',
            'edit_transports',
            'delete_transports',
            'view_promos',
            'delete_reservations',
            'add_groups',
            'edit_groups',
            'delete_groups',
            'view_group_managers',
            'add_group_managers',
            'edit_group_managers',
            'delete_group_managers',
            'add_users',
            'delete_users',
            'add_costs',
            'edit_costs',
            'delete_costs',
            'edit_notes',
            'delete_notes',
            'view_tasks',
            'add_tasks',
            'edit_tasks',
            'delete_tasks',
            'add_fundraisers',
            'delete_fundraisers',
            'view_donors',
            'view_funds'
        ]));

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(array_merge($staff->permissions->pluck('name')->toArray(),[
            'add_campaigns',
            'edit_campaigns',
            'delete_campaigns',
            'add_trips',
            'delete_trips',
            'add_accommodations',
            'edit_accommodations',
            'delete_accommodations',
            'add_regions',
            'edit_regions',
            'delete_regions',
            'add_promos',
            'edit_promos',
            'delete_promos',
            'give_user_permissions',
            'revoke_user_permissions',
            'assign_user_roles',
            'remove_user_roles'
        ]));


        // create roles and assign existing permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo($admin->permissions->pluck('name')->toArray());

        $accountant = Role::create(['name' => 'accountant']);
        $accountant->givePermissionTo([
            'access_backend',
            'view_transactions',
            'add_check_transactions',
            'add_credit_card_transactions',
            'add_credit_transactions',
            'add_cash_transactions',
            'add_transfer_transactions',
            'edit_transactions',
            'delete_transactions',
            'view_donors',
            'add_donors',
            'edit_donors',
            'delete_donors',
            'view_funds',
            'add_funds',
            'edit_funds',
            'delete_funds',
            'view_reports',
            'add_reports',
            'edit_reports',
            'delete_reports'
        ]);

        $medicalModerator = Role::create(['name' => 'medical_moderator']);
        $medicalModerator->givePermissionTo([
            'access_backend',
            'view_reservations',
            'view_medical_releases',
            'add_medical_releases',
            'edit_medical_releases',
            'delete_medical_releases'
        ]);

        $travelDocumentModerator = Role::create(['name' => 'travel_documents_moderator']);
        $travelDocumentModerator->givePermissionTo([
            'access_backend',
            'view_reservations',
            'view_passports',
            'add_passports',
            'edit_passports',
            'delete_passports',
            'view_visas',
            'add_visas',
            'edit_visas',
            'delete_visas'
        ]);

        $generalModerator = Role::create(['name' => 'general_moderator']);
        $generalModerator->givePermissionTo([
            'access_backend',
            'view_reservations',
            'view_credentials',
            'add_credentials',
            'edit_credentials',
            'delete_credentials',
            'view_referrals',
            'add_referrals',
            'edit_referrals',
            'delete_referrals',
            'view_questionnaires',
            'add_questionnaires',
            'edit_questionnaires',
            'delete_questionnaires'
        ]);

        $projectsManager = Role::create(['name' => 'projects_manager']);
        $projectsManager->givePermissionTo([
            'access_backend',
            'view_causes',
            'view_projects',
            'edit_projects',
            'add_causes',
            'edit_causes',
            'delete_causes',
            'add_projects',
            'delete_projects',
            'view_initatives',
            'add_initatives',
            'edit_initatives',
            'delete_initatives'
        ]);
    }
}
