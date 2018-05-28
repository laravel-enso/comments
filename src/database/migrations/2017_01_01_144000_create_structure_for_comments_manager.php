<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForCommentsManager extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'core.comments', 'description' => 'Comments permissions group',
    ];

    protected $permissions = [
        ['name' => 'core.comments.index', 'description' => 'List comments for commentable', 'type' => 0, 'is_default' => true],
        ['name' => 'core.comments.store', 'description' => 'Create comment', 'type' => 1, 'is_default' => true],
        ['name' => 'core.comments.update', 'description' => 'Update edited comment', 'type' => 1, 'is_default' => true],
        ['name' => 'core.comments.destroy', 'description' => 'Delete comment', 'type' => 1, 'is_default' => true],
        ['name' => 'core.comments.getTaggableUsers', 'description' => 'Get taggable users list', 'type' => 0, 'is_default' => true],
    ];
}
