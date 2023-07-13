<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'project_create',
            ],
            [
                'id'    => 18,
                'title' => 'project_edit',
            ],
            [
                'id'    => 19,
                'title' => 'project_show',
            ],
            [
                'id'    => 20,
                'title' => 'project_delete',
            ],
            [
                'id'    => 21,
                'title' => 'project_access',
            ],
            [
                'id'    => 22,
                'title' => 'campaign_create',
            ],
            [
                'id'    => 23,
                'title' => 'campaign_edit',
            ],
            [
                'id'    => 24,
                'title' => 'campaign_show',
            ],
            [
                'id'    => 25,
                'title' => 'campaign_delete',
            ],
            [
                'id'    => 26,
                'title' => 'campaign_access',
            ],
            [
                'id'    => 27,
                'title' => 'lead_create',
            ],
            [
                'id'    => 28,
                'title' => 'lead_edit',
            ],
            [
                'id'    => 29,
                'title' => 'lead_show',
            ],
            [
                'id'    => 30,
                'title' => 'lead_delete',
            ],
            [
                'id'    => 31,
                'title' => 'lead_access',
            ],
            [
                'id'    => 32,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 33,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 34,
                'title' => 'client_create',
            ],
            [
                'id'    => 35,
                'title' => 'client_edit',
            ],
            [
                'id'    => 36,
                'title' => 'client_show',
            ],
            [
                'id'    => 37,
                'title' => 'client_delete',
            ],
            [
                'id'    => 38,
                'title' => 'client_access',
            ],
            [
                'id'    => 39,
                'title' => 'client_management_access',
            ],
            [
                'id'    => 40,
                'title' => 'agency_management_access',
            ],
            [
                'id'    => 41,
                'title' => 'agency_create',
            ],
            [
                'id'    => 42,
                'title' => 'agency_edit',
            ],
            [
                'id'    => 43,
                'title' => 'agency_show',
            ],
            [
                'id'    => 44,
                'title' => 'agency_delete',
            ],
            [
                'id'    => 45,
                'title' => 'agency_access',
            ],
            [
                'id'    => 46,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
