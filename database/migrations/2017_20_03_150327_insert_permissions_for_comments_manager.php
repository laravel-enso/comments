<?php

use App\Permission;
use App\PermissionsGroup;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForCommentsManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionsGroup = PermissionsGroup::whereName('core.comments')->first();

        if ($permissionsGroup) return;

        \DB::transaction(function () {

            $permissionsGroup = new PermissionsGroup([
                'name'        => 'core.comments',
                'description' => 'Comments Permissions Group',
            ]);

            $permissionsGroup->save();

            $permissions = [
                [
                    'name'        => 'core.comments.list',
                    'description' => 'List Comments for Commentable',
                    'type'        => 0,
                ],
                [
                    'name'        => 'core.comments.post',
                    'description' => 'Post Comment',
                    'type'        => 1,
                ],
                [
                    'name'        => 'core.comments.show',
                    'description' => 'Show Comment',
                    'type'        => 0,
                ],
                [
                    'name'        => 'core.comments.update',
                    'description' => 'Update Comment',
                    'type'        => 1,
                ],
                [
                    'name'        => 'core.comments.destroy',
                    'description' => 'Delete Comment',
                    'type'        => 1,
                ],
                [
                    'name'        => 'core.comments.getUsersList',
                    'description' => 'Get Users List For Tagging',
                    'type'        => 0,
                ],
            ];

            $adminRole = Role::whereName('admin')->first();

            foreach ($permissions as $permission) {
                $permission = new Permission($permission);
                $permissionsGroup->permissions()->save($permission);
                $adminRole->permissions()->save($permission);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::transaction(function () {
            $permissionsGroup = PermissionsGroup::whereName('core.comments')->first();
            $permissionsGroup->permissions->each->delete();
            $permissionsGroup->delete();
        });
    }
}
