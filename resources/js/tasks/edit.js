export const Task = () => {

    const breadcrumb = $('#pills-edit-task').find('.breadcrumb-item');

    const selectors = {
        form: '',
        selected_user: '',
        dates: '',
        status: '',
        team: '',
        team_users: '',
        all_users_list: '',
        comments_list: '',
        comment: ''
    };

    const formFields = {
        id: { value: '' },
        title: { value: '', validate_error: [] },
        description: { value: '', validate_error: [] },
        status: { value: '', validate_error: [] },
        team_id: { value: '', validate_error: []},
        user_id: { value: '', validate_error: []}
    }

    const setFormFields = (formName = 'task-edit-form') => {
        selectors.form = $(`#${formName}`);
        selectors.selected_user = $('#selected-user');
        selectors.dates = selectors.form.find('#dates');
        selectors.status = selectors.form.find('#status');
        selectors.team = selectors.form.find('#team_id');
        selectors.team_users = selectors.form.find('#team-users-list');
        selectors.all_users_list = selectors.form.find('#users-list');
        selectors.comments_list = selectors.form.find('#comments-list');
        selectors.comment = selectors.form.find('#comment');

        selectors.form.show();
    }

    const initForm = (id = null) => {
        setFormFields();
        resetForm();
        $('#user-task-edit-form').hide();

        let user = main.getUser();

        if (!id && user.role === 'developer') {
            selectors.form.html('<div>Only a manager can create a task</div>');
            return ;
        }

        let resetBtn = $('#reset-form-btn');
        resetBtn.attr('onclick', `task.initForm()`);

        main.addSelectOptions(selectors.status, tasks.filters.status.options, formFields.status.value);

        let teamsList = Object.fromEntries(
            Object.entries(tasks.filters.team.options).filter(([id, team]) => id !== 0)
        );

        main.addSelectOptions(selectors.team, teamsList, formFields.team_id.value);
        main.addSelectOptions(selectors.team_users, tasks.filters.user.options, formFields.user_id.value);
        main.addSelectOptions(selectors.all_users_list, tasks.filters.user.options, formFields.user_id.value, false);

        if (id) {
            breadcrumb.html(`Edit task - #${id}`);

            let deleteBtn = $('#delete-task-btn');
            deleteBtn.attr('onclick', `task.deleteTask(${id})`);
            deleteBtn.show();

            resetBtn.attr('onclick', `task.show(${id})`);

            let addCommentBtn = selectors.form.find(`#add-comment-btn`);
            addCommentBtn.prop('disabled', false);
            addCommentBtn.attr('onclick', `task.addComment()`);
        }
    }

    const resetForm = () => {
        selectors.selected_user.html('');
        selectors.dates.html('');
        selectors.status.find('option').remove();
        selectors.team.find('option').remove();
        selectors.team_users.find('option').remove();
        selectors.team_users.prop('disabled', true);
        selectors.all_users_list.find('option').remove();
        $('#delete-task-btn').hide();
        selectors.comments_list.html('');
        selectors.comment.val('');

        Object.entries(formFields).forEach(([field, item]) => {
            item.value = '';
            selectors.form.find(`#${field}`).val('');
        });
    }

    const filteredUsersList = str => {
        selectors.all_users_list.find('option').remove();


        let filteredList = Object.fromEntries(
            Object.entries(tasks.filters.user.options)
            .filter(([id, user]) => user.toLowerCase().includes(str.toLowerCase()))
        );

        main.addSelectOptions(selectors.all_users_list, filteredList, formFields.user_id.value, false);
    }

    const setUser = async (userId, teamUser = true) => {
        formFields.user_id.value = userId;
        let userName = '';
        if (userId) {
            userName = tasks.filters.user.options[userId] +
                '   <span onclick="task.resetUser()" class="text-danger pointer"><i class="bi bi-x-circle"></i></span>';
        }


        if (!teamUser) {
            formFields.team_id.value = '';
            team.val('');
            setUsersByTeamId(userId);

        } else {
            selectors.all_users_list.find('option').prop('selected', false);

            filteredUsersList(userName);
        }

        selectors.selected_user.html(userName);
    }

    const resetUser = () => {
        selectors.selected_user.html('');
        formFields.user_id.value = '';

        selectors.team_users.val('');
        filteredUsersList('');
    }

    const setUsersByTeamId = id => {
        let users = (teams.teams_users.hasOwnProperty(id))? teams.teams_users[id] : {};

        selectors.team_users.find('option').remove();
        selectors.team_users.prop('disabled', (!id));

        main.addSelectOptions(selectors.team_users, users, '');

        if (!id) {
            formFields.user_id.value = '';
            selectors.all_users_list.find('option').prop('selected', false);
            filteredUsersList('');
        }
    }

    const show = async id => {
        let {item, error} = await client.getListItem('tasks', id);

        if (error) return main.checkErrorStatus(error);

        initForm(id);

        breadcrumb.html(`Edit task - #${id}`);

        for (let [field, items] of Object.entries(formFields)) {
            formFields[field].value = item[field];
            selectors.form.find(`#${field}`).val(`${item[field]}`);
        }

        let user = tasks.filters.user.options[formFields.user_id.value]
        if (typeof user === 'undefined') user = '';

        if (formFields.team_id.value) {
            setUsersByTeamId(formFields.team_id.value);

            selectors.team_users.prop('disabled', false);
            selectors.team_users.val(formFields.user_id.value);

            filteredUsersList(user);
        } else {
            filteredUsersList(user);
        }

        if (user) {
            user = user + '   <span onclick="task.resetUser()" class="text-danger pointer"><i class="bi bi-x-circle"></i></span>';
        }
        selectors.selected_user.html(user);

        $('#dates').append(
            `<div>Created : ${item.created_at}</div>` +
            `<div>Updated : ${item.updated_at}</div>`
        );

        if (item.comments.length > 0) setComments(item.comments);

        main.openTab('pills-edit-task');
    }

    const showUserTask = async id => {
        setFormFields('user-task-edit-form');
        $('#task-edit-form').hide();

        selectors.comments_list.find('.task-comments').html('');
        selectors.comment.val('');
        selectors.form.find('#add-comment-btn').attr('onclick', `task.addComment(${id})`);

        selectors.status.find('option').remove();
        selectors.dates.html('');

        let {item, error} = await client.getListItem('tasks', id);

        if (error) return main.checkErrorStatus(error);

        breadcrumb.html(`Edit task - #${id}`);

        for (let [field, items] of Object.entries(formFields)) {
            formFields[field].value = item[field];
        }

        selectors.dates.append(
            `<div>Created : ${item.created_at}</div>` +
            `<div>Updated : ${item.updated_at}</div>`
        );

        selectors.form.find('#title').html(`${item.title}`);
        selectors.form.find('#description').html(`${item.description}`);

        main.addSelectOptions(selectors.status, tasks.filters.status.options, formFields.status.value);

        if (item.comments.length > 0) setComments(item.comments);

        main.openTab('pills-edit-task');
    }

    const setComments = comments => {
        selectors.comments_list.html('');

        comments.forEach(comment => {
            selectors.comments_list.append(
                '<div>' +
                    '<div class="d-flex align-items-center gap-2">' +
                        `<span onclick="task.deleteComment(${comment.id})" class="text-danger pointer" title="Delete comment"><i class="bi bi-trash"></i></span>` +
                        `<small class="user-comment">${comment.user} : ${comment.created_at}</small>` +
                    '</div>' +
                    `<div class="ms-4">${comment.content}</div>` +
                '</div>'
            );
        });
    }

    const deleteTask = async id => {
        if (confirm(`Confirm action. Delete task - ${id}`)) {
            let {item, error} = await client.sendForm(`tasks/${id}`, 'delete');

            if (error) return main.checkErrorStatus(error);

            await tasks.render();

            main.openTab('pills-tasks');

            main.showAlert('success', `Task - [ ${id} ] successfully deleted !`);

            resetForm();
        }
    }

    const addComment = async (withTask = null) => {
        if (selectors.comment.hasClass('isInvalid')) {
            selectors.comment.removeClass('isInvalid');
            $('#task-comments').find('.text-danger').remove();
        }

        let taskId = (!withTask)? formFields.id.value : withTask;

        let {item, error} = await client.sendForm(`tasks/${taskId}/comments`, 'post',
            {content: selectors.comment.val()}
        );

        if (error) {
            if (error.status === 422) {
                selectors.comment.addClass('isInvalid');
                $('#task-comments').append(
                    `<span class="text-danger">${error.response.data.errors.content[0]}</span>`
                );
            } else {
                return main.checkErrorStatus(error);
            }
        }

        selectors.comment.val('');

        if (!withTask) return await show(formFields.id.value);
    }

    const deleteComment = async id => {
        if (confirm(`Confirm action. Delete this comment ?`)) {
            let {item, error} = await client.sendForm(`comments/${id}`, 'delete');

            if (error) return main.checkErrorStatus(error);

            await show(formFields.id.value);
        }
    }

    const sendTaskForm = async event => {
        event.preventDefault();

        let formData = {};

        for (let [field, items] of Object.entries(formFields)) {
            let input = selectors.form.find(`#${field}`);
            formData[field] = input.val();

            formFields[field].validate_error = [];

            if (input.hasClass('isInvalid')) {
                input.removeClass('isInvalid');
                selectors.form.find(`#input-group-${field}`).find('.text-danger').remove();
            }
        }
        formData.user_id = formFields.user_id.value;

        let id = formFields.id.value;
        let method = (id)? 'put' : 'post';
        let url = (id)? `tasks/${id}` : 'tasks';

        let {item, error} = await client.sendForm(url, method, formData);

        if (error) {
            if (error.status === 422) {
                Object.entries(error.response.data.errors).forEach(([name, errors]) => {
                    formFields[name].validate_error = errors;
                });
                return renderErrors();

            } else {
                return main.checkErrorStatus(error);
            }
        }

        await tasks.render();

        if (selectors.comment.val() !== '') await addComment(item.id);

        await show(item.id);

        let message = (id)? `Task - [ ${id} ] successfully updated !` : `Task "${formData.title}" successfully created !`;
        main.showAlert('success', message);

    }

    const updateTaskForm = async () => {
        event.preventDefault();

        let statusBlock = $(`#input-group-status`);

        if (selectors.status.hasClass('isInvalid')) {
            selectors.status.removeClass('isInvalid');
            statusBlock.find('.text-danger').remove();
        }

        formFields.status.value = selectors.status.val();

        let formData = Object.keys(formFields).reduce((acc, key) => {
            acc[key] = formFields[key].value;
            return acc;
        }, {});

        let {item, error} = await client.sendForm(`tasks/${formFields.id.value}`, 'put', formData);

        if (error) {
            if (error.status === 422) {
                selectors.status.addClass('isInvalid');
                statusBlock.append(
                    `<span class="text-danger">${error.response.data.errors.content[0]}</span>`
                );
                return ;
            } else {
                return main.checkErrorStatus(error);
            }
        }

        if (selectors.comment.val() !== '') await addComment(item.id);

        await tasks.render();

        await showUserTask(item.id);

        main.showAlert('success', `Task - [ ${item.id} ] successfully updated !`);
    }

    const renderErrors = () => {
        for (let [field, items] of Object.entries(formFields)) {
            if (items.validate_error && items.validate_error.length > 0) {
                selectors.form.find(`#${field}`).addClass('isInvalid');
                selectors.form.find(`#input-group-${field}`).append(
                    `<span class="text-danger">${items.validate_error[0]}</span>`
                );
            }
        }

        return null;
    }

    return {
        initForm,
        show,
        showUserTask,
        deleteTask,
        resetForm,
        filteredUsersList,
        setUser,
        resetUser,
        setUsersByTeamId,
        sendTaskForm,
        updateTaskForm,
        addComment,
        deleteComment
    };
}
