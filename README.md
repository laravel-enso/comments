# Comments

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d96ab52d782d46b9a94e00ea6059b34c)](https://www.codacy.com/app/laravel-enso/comments?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/comments&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://github.styleci.io/repos/85583597/shield?branch=master)](https://github.styleci.io/repos/85583597)
[![License](https://poser.pugx.org/laravel-enso/comments/license)](https://packagist.org/packages/laravel-enso/comments)
[![Total Downloads](https://poser.pugx.org/laravel-enso/comments/downloads)](https://packagist.org/packages/laravel-enso/comments)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/comments/version)](https://packagist.org/packages/laravel-enso/comments)

Comments Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

This package works exclusively within the [Enso](https://github.com/laravel-enso/Enso) ecosystem.

There is a front end implementation for this this api in the [accessories](https://github.com/enso-ui/accessories) package.

For live examples and demos, you may visit [laravel-enso.com](https://www.laravel-enso.com)

[![Watch the demo](https://laravel-enso.github.io/comments/screenshots/bulma_018_thumb.png)](https://laravel-enso.github.io/comments/videos/bulma_demo_01.webm)

<sup>click on the photo to view a short demo in compatible browsers</sup>

## Installation

Comes pre-installed in Enso.

## Features

The package offers a quick and easy flow for adding comments to any model.

- gives the possibility to add, update and delete comments
- has the option of tagging other users in the comments using `@` and the user name
- users are notified via push [Notifications](https://github.com/laravel-enso/Notifications) when they are tagged
- uses its own policies to ensure users edit comments only when they are allowed to do so
- uses [TrackWho](https://github.com/laravel-enso/TrackWho) to keep track of the users that are posting comments
- depends on [Avatar Manager](https://github.com/laravel-enso/Avatars) to display user avatars, when available
- uses a light, internal mechanism for tagged user auto-completion
- polymorphic relationships are used, which makes it possible to attach comments to any other entity

### Configuration & Usage

Be sure to check out the full documentation for this package available at [docs.laravel-enso.com](https://docs.laravel-enso.com/backend/comments-manager.html)

### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
