# Comments Manager

Comments Manager for commentables.

## Don't forget to

artisan vendor:publish --tag=comments-migrations
artisan vendor:publish --tag=comments-component

php artisan migrate

include the vue-component in your app.js

run gulp

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