@extends('layouts.app')

@section('content')
<div class="container">
    <div class="content d-flex flex-column gap-4">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-tasks" role="tabpanel" aria-labelledby="pills-tasks-tab">
                <div class="breadcrumb-card">
                    {{ Breadcrumbs::render('tasks-list') }}
                </div>
                <div id="tasks-list"></div>
            </div>
            <div class="tab-pane fade" id="pills-edit-task" role="tabpanel" aria-labelledby="pills-edit-task-tab">
                <div class="breadcrumb-card">
                    {{ Breadcrumbs::render('edit-task') }}
                </div>
                <div id="edit-task-content">
                    @include('task-edit-form')
                    @include('user-task-form')
                </div>
            </div>
            <div class="tab-pane fade" id="pills-teams" role="tabpanel" aria-labelledby="pills-teams-tab">
                <div class="breadcrumb-card">
                    {{ Breadcrumbs::render('teams-list') }}
                </div>
                <div id="teams-list"></div>
            </div>
            <div class="tab-pane fade" id="pills-edit-team" role="tabpanel" aria-labelledby="pills-edit-team-tab">
                <div class="breadcrumb-card">
                    {{ Breadcrumbs::render('edit-team') }}
                </div>
                <div id="edit-team-content">
                    @include('team-edit-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="module">

        $(() => {
            let user = main.getUser();

            if (user) {
                $('#user-data').html(
                    '<div class="d-flex align-items-center">' +
                        `<div class="ps-2 pe-2">${user.name} (${user.role})</div>` +
                        '<div class="ps-2 pe-2">' +
                            `<button onclick="client.auth('logout')" type="button" class="btn btn-sm btn-secondary">Log out</a>` +
                        '</div>' +
                    '</div>'
                );

                tasks.render();
                teams.render();

                $('#task-edit-form').on('submit', e => task.sendTaskForm(e));
                $('#team-edit-form').on('submit', e => team.sendTeamForm(e));
                $('#user-task-edit-form').on('submit', e => task.updateTaskForm(e));

            } else {
                return window.location.href = window.location.origin + '/login';
            }
        });
    </script>
@endsection
