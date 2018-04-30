<?php

Breadcrumbs::register('backend.index', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('home'));
});

Breadcrumbs::register('backend.users.index', function ($breadcrumbs) {
    $breadcrumbs->parent('backend.index');
    $breadcrumbs->push('Users', route('backend.users.index'));
});