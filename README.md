<!--h-->
# Comments Manager

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d96ab52d782d46b9a94e00ea6059b34c)](https://www.codacy.com/app/laravel-enso/CommentsManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/CommentsManager&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/85583597/shield?branch=master)](https://styleci.io/repos/85583597)
[![License](https://poser.pugx.org/laravel-enso/commentsmanager/license)](https://https://packagist.org/packages/laravel-enso/commentsmanager)
[![Total Downloads](https://poser.pugx.org/laravel-enso/commentsmanager/downloads)](https://packagist.org/packages/laravel-enso/commentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/commentsmanager/version)](https://packagist.org/packages/laravel-enso/commentsmanager)
<!--/h-->

Comments Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/commentsmanager/screenshots/Selection_018_thumb.png)](https://laravel-enso.github.io/commentsmanager/videos/demo_01.webm)

<sup>click on the photo to view a short demo in compatible browsers</sup>


### Features

The package offers a quick and easy flow for adding comments to any model.

- gives the possibility to add, update, delete comments
- has the option of tagging other users in the comments using `@` and the user name
- users are notified via push [Notifications](https://github.com/laravel-enso/Notifications) when they are tagged
- uses its own policies to ensure users edit comments only when they are allowed to do so
- uses [TrackWho](https://github.com/laravel-enso/TrackWho) to keep track of the users that are posting comments
- depends on [Avatar Manager](https://github.com/laravel-enso/AvatarManager) to display user avatars, when available
- uses [At.js](https://github.com/ichord/At.js) for tagged user auto-completion

### Under the Hood
- polymorphic relationships are used, which makes it possible to attach comments to any other entity
- within the entity to which we want to attach comments, we must use the `Commentable` trait

### Installation Steps

1. Add `LaravelEnso\CommentsManager\CommentsManagerServiceProvider::class` to `config/app.php`

2. Run the migrations `php artisan migrate`

3. Publish the config file with `php artisan vendor:publish --tag=comments-config`. Define the `'model_alias' => 'App\Model'` mapping in the `config/comments.php` file.

4. Publish the VueJS component with `php artisan vendor:publish --tag=comments-component`

5. Include the VueJS component in your `app.js` file and then compile with `gulp` / `npm run dev`

6. Add `use Commentable` in the Model that need comments and import the trait. This way you can call the `$model->comments` relationship

7. Because users make comments, and users can tag other users, you need to add `use Comments` trait the `User` model. Don't forget to import it

8. If you need to customize the CommentTagNotification you need to publish it first with `php artisan vendor:publish --tag=comments-notification`

### Use

```
<comments :id="modelId"
    type="model_alias"
    :paginate="5">
</comments>
```

### Options

- `type` - the commentable model alias (required) you set at the installation step #3
- `id` - the id of the commentable model (required)
- `paginate` - the paginate size, default value is 5 (optional)
- `header-class` - header class for the box element: info (default option) / default / primary / warning / danger / default

### Publishes
- `php artisan vendor:publish --tag=comments-config` - configuration file
- `php artisan vendor:publish --tag=comments-component` - the VueJS component
- `php artisan vendor:publish --tag=comments-notification` - the queueable notification sent to the tagged users
- `php artisan vendor:publish --tag=enso-update` - a common alias for when wanting to update the VueJS component,
once a newer version is released
- `php artisan vendor:publish --tag=enso-config` - a common alias for when wanting to update the config,
once a newer version is released

<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->