<form id="task-edit-form">
    @csrf
    <div class="content d-flex flex-column gap-4">
        <div id="task-panel-btns" class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-success">Save</button>
            <button onclick="task.resetForm()" type="button" id="reset-form-btn" class="btn btn-warning">Clear</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">< Back</a>
            <button type="button" id="delete-task-btn" class="btn btn-danger" style="display: none">Delete</button>
            <div class="fs-5">
                <span>Selected user : </span>
                <span class="fw-bold" id="selected-user"></span>
            </div>
        </div>
        <div id="dates"></div>
        <div class="d-flex flex-column gap-3">
            <div class="row">
                <div class="col-sm-7 d-flex flex-column gap-3">
                    <div id="input-group-title">
                        <label for="title">
                            <span class="text-danger">*</span> Title
                        </label>
                        <input type="text" name="title" id="title" class="form-control border-dark-subtle">
                    </div>
                    <div id="input-group-description">
                        <label for="description">
                            <span class="text-danger">*</span> Description
                        </label>
                        <textarea name="description" id="description" class="form-control border-dark-subtle" rows="8"></textarea>
                    </div>
                    <div id="task-comments" class="d-flex flex-column gap-2">
                        <div>
                            <div>Comments</div>
                            <div id="comments-list" class="task-comments"></div>
                        </div>
                        <div>
                            <div>
                                <label for="comment">Add comment</label>
                            </div>
                            <div class="d-flex gap-2">
                                <input name="comment" id="comment" class="form-control">
                                <button id="add-comment-btn" type="button" class="btn btn-primary" disabled>Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 d-flex flex-column gap-3">
                    <div id="input-group-status" class="col-sm-7">
                        <label for="status">
                            <span class="text-danger">*</span> Status
                        </label>
                        <select name="status" id="status" class="form-select border-dark-subtle">
                            <option></option>
                        </select>
                    </div>
                    <div id="input-group-team_id" class="col-sm-7">
                        <label for="team_id">Team</label>
                        <select onchange="task.setUsersByTeamId(this.value)" name="team_id" id="team_id" class="form-select border-dark-subtle">
                            <option></option>
                        </select>
                    </div>
                    <div id="input-group-team-users-list" class="col-sm-7">
                        <label for="team-users-list">Team user</label>
                        <select onchange="task.setUser(this.value)" id="team-users-list" class="form-select border-dark-subtle" disabled>
                            <option></option>
                        </select>
                    </div>
                    <div id="input-group-all-user-list" class="col-sm-7 d-flex flex-column gap-3">
                        <div>
                            <span>Or User from list:</span>
                            <input onkeyup="task.filteredUsersList(this.value)" id="searchUser" type="text" class="form-control form-control-sm border-dark-subtle" placeholder="Search user">
                        </div>
                        <div>
                            <select onchange="task.setUser(this.value, false)" multiple id="users-list" class="form-control border-dark-subtle users-list"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
