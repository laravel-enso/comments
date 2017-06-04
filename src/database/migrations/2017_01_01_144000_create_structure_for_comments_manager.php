<?php

use LaravelEnso\Core\app\Classes\StructureManager\StructureMigration;

class CreateStructureForCommentsManager extends StructureMigration
{
    protected $permissionsGroup = [
        'name' => 'core.comments', 'description' => 'Comments Permissions Group',
    ];

    protected $permissions = [
        ['name' => 'core.comments.index', 'description' => 'List Comments for Commentable', 'type' => 0],
        ['name' => 'core.comments.store', 'description' => 'Create Comment', 'type' => 1],
        ['name' => 'core.comments.update', 'description' => 'Update Comment', 'type' => 1],
        ['name' => 'core.comments.destroy', 'description' => 'Delete Comment', 'type' => 1],
        ['name' => 'core.comments.getTaggableUsers', 'description' => 'Get Taggable Users', 'type' => 0],
    ];
}
