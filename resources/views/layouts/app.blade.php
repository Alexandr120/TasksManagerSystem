<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <main>
            <div class="header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="ps-2 pe-2">
                        <span class="fs-3 fw-bold">TASK MANAGEMENT SYSTEM</span>
                    </div>
                    <div id="user-data" class="ps-2 pe-2"></div>
                </div>
                <div id="header-menu">
                    <ul class="nav nav-pills nav-fill justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="custom-nav-link active" id="pills-tasks-tab" data-bs-toggle="pill" data-bs-target="#pills-tasks"
                                    type="button" role="tab" aria-controls="pills-tasks" aria-selected="true">
                                {{ __('Tasks list') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button onclick="task.initForm()" class="custom-nav-link" id="pills-edit-task-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-task"
                                    type="button" role="tab" aria-controls="pills-edit-task" aria-selected="false">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit task') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="custom-nav-link" id="pills-teams-tab" data-bs-toggle="pill" data-bs-target="#pills-teams"
                                    type="button" role="tab" aria-controls="pills-teams" aria-selected="false">
                                {{ __('Teams list') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button onclick="team.initForm()" class="custom-nav-link" id="pills-edit-team-tab" data-bs-toggle="pill" data-bs-target="#pills-edit-team"
                                    type="button" role="tab" aria-controls="pills-edit-team" aria-selected="false">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit team') }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            @yield('content')

        </main>
    </div>

    @if(View::hasSection('script'))
        @yield('script')
    @endif

    <script type="module">
        $(() => {
            $('.custom-nav-link').click(() => main.resetAlertMessage());

            let user = localStorage.getItem('user');
            if (!user) {
                $('#header-menu').remove();
            } else {
                main.checkUserRole(user);
            }

        });

    </script>

</body>
</html>
