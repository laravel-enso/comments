# Comments Manager
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d96ab52d782d46b9a94e00ea6059b34c)](https://www.codacy.com/app/laravel-enso/CommentsManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/CommentsManager&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/85583597/shield?branch=master)](https://styleci.io/repos/85583597)
[![Total Downloads](https://poser.pugx.org/laravel-enso/commentsmanager/downloads)](https://packagist.org/packages/laravel-enso/commentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/commentsmanager/version)](https://packagist.org/packages/laravel-enso/commentsmanager)

Comments Manager for Laravel Enso. This package creates a Comment model that has a `commentable` morphTo relation.

### Installation Steps

1. Add `LaravelEnso\CommentsManager\CommentsManagerServiceProvider::class` to `config/app.php`. (included if you use LaravelEnso/coreplus)

2. Run migrations.

3. Publish the config file with `php artisan vendor:publish --tag=comments-config`.

4. Publish the vue component with `php artisan vendor:publish --tag=comments-component`.

5. Include the vue-component in your app.js.

6. Run gulp.

7. Add the following relationship to the Model that need comments.

```php
public function comments()
{
    return $this->morphMany('LaravelEnso\CommentsManager\app\Models\Comment', 'commentable');
}
```

8. Define the 'model' => 'App\Model' mapping in the config.

9. Because users make comments, and users can tag other users, you need to add the following relationships to the User model.

```php
public function comments()
{
    return $this->hasMany('LaravelEnso\CommentsManager\app\Models\Comment', 'created_by');
}

public function comment_tags()
{
    return $this->belongsToMany('LaravelEnso\CommentsManager\app\Models\Comment');
}
```

10. If you need to customize the CommentTagNotification you need to publish it first with `php artisan vendor:publish --tag=comments-notification`.

### You can

Build a partial to use with the vue component in your app/resources/views/partials/comments-labels.blade.php

```
<span slot="comments-manager-title">{{ __("Comments") }}</span>
<span slot="comments-manager-load-more">{{ __("more") }}</span>
```

and then you can use

```
<comments-manager :id="modelId"
    type="model"
    :paginate="5">
    @include('partials.comments-labels')
</comments-manager>
```

### Options

	`type` - the commentable model (required)
	`id` - the id of the commentable model (required)
	`paginate` - the paginate size, default value is 5 (optional)
    `header-class` - header class for the box element: info (default option) / default / primary / warning / danger / default

### Contributions

...are welcome