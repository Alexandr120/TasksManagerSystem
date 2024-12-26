<form id="user-task-edit-form">
    @csrf
    <div class="content d-flex flex-column gap-4">
        <div id="task-panel-btns" class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">< Back</a>
        </div>
        <div id="dates"></div>
        <div class="row">
            <div class="col-sm-6 d-flex flex-column gap-3">
                <div id="input-group-status" class="col-sm-7">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select border-dark-subtle">
                        <option></option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <div class="fw-bold">Title:</div>
                    <div id="title" class="border-dark-subtle"></div>
                </div>
                <div>
                    <div class="fw-bold">Description:</div>
                    <div id="description"></div>
                </div>
            </div>
            <div id="task-comments" class="col-sm-6 d-flex flex-column gap-2">
                <div>
                    <div>Comments</div>
                    <div id="comments-list" class="task-comments"></div>
                </div>
                <div>
                    <div>
                        <label for="comment">Add comment</label>
                    </div>
                    <div class="d-flex gap-2">
                        <input name="comment" id="comment" class="form-control border-dark-subtle">
                        <button id="add-comment-btn" type="button" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
