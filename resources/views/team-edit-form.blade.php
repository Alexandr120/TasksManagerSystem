<form id="team-edit-form">
    @csrf
    <div class="content d-flex flex-column gap-4">
        <div id="task-panel-btns" class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-success">Save</button>
            <button onclick="team.resetForm()" type="button" id="reset-form-btn" class="btn btn-warning">Clear</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">< Back</a>
            <button type="button" id="delete-team-btn" class="btn btn-danger" style="display: none">Delete</button>
        </div>

        <div class="d-flex">
            <div class="d-flex flex-column gap-3 col-sm-6 pe-3">
                <div id="input-group-name">
                    <label for="title">
                        <span class="text-danger">*</span> Name
                    </label>
                    <input type="text" name="name" id="name" class="form-control border-dark-subtle">
                </div>

                <div>
                    <div>
                        <span class="fs-4">Team users :</span>
                    </div>
                    <div id="current-users" class="current-team-users"></div>
                </div>
            </div>
            <div class="d-flex flex-column gap-2 col-sm-6 ps-3">
                <div class="d-flex flex-column gap-0">
                    <span class="fs-4">All users :</span>
                    <small>( To add, drag a user )</small>
                </div>
                <div id="all-users" class="all-users-list"></div>
            </div>
        </div>

    </div>
</form>
