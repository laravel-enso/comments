<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForComments extends Migration
{
    protected $permissions = [
        ['name' => 'core.comments.index', 'description' => 'List comments for commentable', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.comments.store', 'description' => 'Create comment', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.comments.update', 'description' => 'Update edited comment', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.comments.destroy', 'description' => 'Delete comment', 'type' => Types::Write, 'is_default' => true],
    ];
}
