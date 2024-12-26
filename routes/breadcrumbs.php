<?php
Breadcrumbs::for('tasks-list', function ($trail) {
    $trail->push('Tasks list', route('home'));
});

Breadcrumbs::for('edit-task', function ($trail) {
    $trail->push('Add new task', route('home'));
});

Breadcrumbs::for('teams-list', function ($trail) {
    $trail->push('Teams list', route('home'));
});

Breadcrumbs::for('edit-team', function ($trail) {
    $trail->push('Add new team', route('home'));
});

