# Comments Manager

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d96ab52d782d46b9a94e00ea6059b34c)](https://www.codacy.com/app/laravel-enso/CommentsManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/CommentsManager&utm_campaign=badger)

Comments Manager for commentables.

## Don't forget to

artisan vendor:publish --tag=comments-migrations
artisan vendor:publish --tag=comments-component

php artisan migrate

include the vue-component in your app.js

run gulp

add to the User model

```php
	public function comments()
    {
        return $this->hasMany('LaravelEnso\CommentsManager\App\Models\Comment');
    }

    public function comments_tags()
    {
        return $this->belongsToMany('LaravelEnso\CommentsManager\App\Models\Comment');
    }
```

## You can

Build a partial to use with the vue component in your app/resources/views/partials/comments-labels.blade.php

```
<span slot="comments-manager-title">{{ __("Comments") }}</span>
<span slot="comments-manager-load-more">{{ __("more") }}</span>
```

and then you can use

```
<comments-manager options>
@include('partials.comments-labels')
</comments-manager>
```