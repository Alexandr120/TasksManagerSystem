<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = new Collection([
            'read_tasks_list' => 'read tasks list',
            'read_task'=> 'read task',
            'create_task' => 'create task',
            'update_task' => 'update task',
            'delete_task' => 'delete task',
            'read_teams_list' => 'read teams list',
            'read_team'=> 'view team',
            'create_team' => 'create team',
            'update_team' => 'update team',
            'delete_team' => 'delete team',
            'update_team_users' => 'update team users',
            'read_task_comments' => 'read task comments',
            'create_comment' => 'create comment',
            'update_comment' => 'update comment'
        ]);

        $permissionsByRole = new Collection([
            'manager' => $permissions->all(),
            'developer' => $permissions->except([
                'create_task',
                'delete_task',
                'read_team',
                'create_team',
                'update_team',
                'delete_team',
                'read_task_comments'
            ])->all()
        ]);

        $permissions->map(function ($permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        });

        $permissionsByRole->map(function ($permissions, $role) {
            $role = Role::create(['name' => $role, 'guard_name' => 'api']);
            $role->givePermissionTo($permissions);
        });

        $teamsCollection = collect([
            'Back-end developers', 'Front-end developers', 'Full-stack developers'
        ]);

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('password')
        ]);

        $manager->roles()->attach(Role::findByName('manager', 'api')->id);

        Task::factory(5)->create()->each(function ($task) {
            $task->update(['status' => TaskStatus::PENDING()]);
        });

        $teamsCollection->map(function ($teamName) {
            $team = Team::create(['name' => $teamName]);

            User::factory(3)->create()->each(function ($user, $key) use ($team) {
                //Give role to user
                $user->roles()->attach(Role::findByName('developer', 'api')->id);

                // Add user to team
                $team->users()->attach($user->id);

                // Create task for user
                $tasks = Task::factory(5)->make()->map(function ($task, $key) use ($team) {
                    $task->team_id = $team->id;
                    return $task;
                });

                $user->tasks()->saveMany($tasks);

                //Add some comment for first task of user
                $tasks->each(function ($task) {
                    $task->team->users->each(function ($user) use ($task) {
                        $task->comments()->create([
                            'user_id' => $user->id,
                            'content' => 'Team member comment - ' . $task->user->name . ' for task -> ' . $task->title
                        ]);
                    });
                });

            });
        });
    }
}
