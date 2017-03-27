<?php

use LaravelEnso\Core\app\Classes\StructureManager\StructureMigration;

class CreateStructureForCommentsManager extends StructureMigration
{
    protected $permissionsGroup = [
        'name' => 'core.comments', 'description' => 'Comments Permissions Group',
    ];

    protected $permissions = [
        ['name' => 'core.comments.list', 'description' => 'List Comments for Commentable', 'type' => 0],
        ['name' => 'core.comments.post', 'description' => 'Post Comment', 'type' => 1],
        ['name' => 'core.comments.show', 'description' => 'Show Comment', 'type' => 0],
        ['name' => 'core.comments.update', 'description' => 'Update Comment', 'type' => 1],
        ['name' => 'core.comments.destroy', 'description' => 'Delete Comment', 'type' => 1],
        ['name' => 'core.comments.getUsersList', 'description' => 'Get Users List For Tagging', 'type' => 0],
    ];
}
