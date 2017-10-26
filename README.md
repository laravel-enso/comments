<!--h-->
# Comments Manager

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d96ab52d782d46b9a94e00ea6059b34c)](https://www.codacy.com/app/laravel-enso/CommentsManager?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/CommentsManager&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/85583597/shield?branch=master)](https://styleci.io/repos/85583597)
[![License](https://poser.pugx.org/laravel-enso/commentsmanager/license)](https://https://packagist.org/packages/laravel-enso/commentsmanager)
[![Total Downloads](https://poser.pugx.org/laravel-enso/commentsmanager/downloads)](https://packagist.org/packages/laravel-enso/commentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/commentsmanager/version)](https://packagist.org/packages/laravel-enso/commentsmanager)
<!--/h-->

Comments Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/commentsmanager/screenshots/bulma_018_thumb.png)](https://laravel-enso.github.io/commentsmanager/videos/bulma_demo_01.webm)

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

The component is already included in the Enso install and should not require any additional installation steps.


### Use

1. Define the `'model_alias' => 'App\Model'` mapping in the `config/enso/comments.php` file.
2. Add the `Commentable` trait in the Model to which you need to add comments. 
    You can then use the `$model->comments` relationship
3. Since users post comments, and users can tag other users, the `User` model has the `Comments` trait, 
    which give you access to the user's comments, as well as the comments he's tagged in 
4. If you need to customize the `CommentTagNotification` you need to publish it first with
    `php artisan vendor:publish --tag=comments-notification`
5. Include the component in your pages/components
    ```
    <comments :id="modelId"
        type="model_alias"
        :paginate="5">
    </comments>
    ```

### Options

- `id` - the id of the commentable model | required
- `type` - the commentable model alias you set at the installation step #3 | required
- `paginate` - the paginate size, default value is 5 | optional
- `open` - boolean flag, makes the component start collapsed (default) or open | optional
- `title` - title for the component, if nothing is given, 'Comments' is used | optional

### Publishes
- `php artisan vendor:publish --tag=comments-config` - configuration file
- `php artisan vendor:publish --tag=vue-components` - the VueJS components
- `php artisan vendor:publish --tag=comments-notification` - the queueable notification sent to the tagged users
- `php artisan vendor:publish --tag=enso-update` - a common alias for when wanting to update the VueJS component,
once a newer version is released, can be used with the `--force` flag
- `php artisan vendor:publish --tag=enso-assets` - a common alias for when wanting to update the assets,
once a newer version is released, can be used with the `--force` flag


### Notes

The [Laravel Enso](https://github.com/laravel-enso/Enso) package comes with this package included.

Depends on:
 - [Core](https://github.com/laravel-enso/Core) for middleware and user model 
 - [Structure manager](https://github.com/laravel-enso/StructureManager) for the migrations
 - [TrackWho](https://github.com/laravel-enso/TrackWho) for keeping track of the users making the changes to each contact
 - [VueComponents](https://github.com/laravel-enso/VueComponents) for the accompanying VueJS components
 - [AvatarManager](https://github.com/laravel-enso/AvatarManager) for showing the users' avatars

<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->