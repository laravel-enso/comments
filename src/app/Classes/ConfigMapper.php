<?php

namespace LaravelEnso\CommentsManager\app\Classes;

use LaravelEnso\Helpers\app\Classes\MorphableConfigMapper;

class ConfigMapper extends MorphableConfigMapper
{
    protected $configPrefix = 'enso.comments';
    protected $morphableKey = 'commentables';
}
