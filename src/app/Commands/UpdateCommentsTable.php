<?php

namespace LaravelEnso\CommentsManager\app\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateCommentsTable extends Command
{
    protected $signature = 'enso:comments:update-table';

    protected $description = 'This command will make the `updated_by` column nullable';

    public function handle()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->integer('updated_by')->unsigned()->nullable()
                ->change();
        });

        $this->info('The `updated_by` column was successfully changed to `nullable()`.');
    }
}
