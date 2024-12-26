import Sortable from 'sortablejs';
const sortable = Sortable;

export const Team = () => {

    const id = '';

    const breadcrumb = $('#pills-edit-team').find('.breadcrumb-item');

    const form = $('#team-edit-form');

    const name = form.find('#name');

    const current_users_block = form.find('#current-users');

    const all_users_block = form.find('#all-users');

    const initForm = () => {
        resetForm();
        initSorted();
        setAllUsers(tasks.filters.user.options);
        $('#delete-team-btn').hide();
    }

    const resetForm = () => {
        name.val('');
        current_users_block.html('');
        all_users_block.html('');
    }

    const initSorted = () => {
        let options = {
            group: 'shared',
            sort: false,
            animation: 150
        };

        new sortable.create(current_users_block[0], options);
        new sortable.create(all_users_block[0], options);
    }

    const setAllUsers = all_users => {
        Object.entries(all_users).forEach(([id, user]) => {
            all_users_block.append(
                `<div data-user="${id}" class="pointer">${user}</div>`
            );
        });
    }

    const setCurrentUsers = users => {
        Object.entries(users).forEach(([id, user]) => {
            current_users_block.append(
                `<div data-user="${id}" class="pointer">${user}</div>`
            );
        });

        let available_users = Object.fromEntries(
            Object.entries(tasks.filters.user.options).filter(([id, user]) => !users.hasOwnProperty(id))
        );

        all_users_block.html('');
        setAllUsers(available_users);
    }

    const deleteTeam = async id => {
        if (confirm(`Confirm action. Delete team - ${id}`)) {
            let {item, error} = await client.sendForm(`teams/${id}`, 'delete');

            if (error) return main.checkErrorStatus(error);
        }

        await teams.render();

        let message = `Team - [ ${id} ] successfully deleted !`;
        main.showAlert('success', message);

        main.openTab('pills-teams');
    }

    const removeUser = async (teamId, id) => {
        if (confirm(`Confirm action. Remove this user - ${id}`)) {
            let {item, error} = await client.sendForm(`teams/${teamId}/users/${id}`, 'delete');

            if (error) return main.checkErrorStatus(error);

            await teams.render();

            $(`#team-users-${teamId}`).addClass('show');
        }
    }

    const show = async (id, team = null) => {
        breadcrumb.html(`Edit task - #${id}`);

        if (!team) {
            let {item, error} = await client.getListItem('teams', id);

            if (error) return main.checkErrorStatus(error);

            team = item;
        }

        initForm();

        name.val(team.name);

        setCurrentUsers(team.users);

        let deleteBtn = $('#delete-team-btn');
        deleteBtn.attr('onclick', `onclick=team.deleteTeam(${id})`);
        deleteBtn.show();

        main.openTab('pills-edit-team');
    }

    const sendTeamForm = async event => {
        event.preventDefault();

        if (name.hasClass('isInvalid')) {
            name.removeClass('isInvalid');
            form.find(`#input-group-name`).remove();
        }

        let method = (id)? 'put' : 'post';
        let url = (id)? `teams/${id}` : 'teams';

        let {item, error} = await client.sendForm(url, method, { name: name.val() });

        if (error) {
            if (error.status === 422) {
               name.addClass('isInvalid');
               form.find(`#input-group-name`).append(
                   `<span class="text-danger">${error.response.data.errors.name[0]}</span>`
               );
            } else {
                return main.checkErrorStatus(error);
            }
        }

        name.val('');

        await syncCommand(item.id);

        await teams.render();

        let message = (id)? `Team - [ ${item.id} ] successfully updated !` : `Team "${name.val()}" successfully created !`;
        main.showAlert('success', message);
    }

    const syncCommand = async teamId => {
        let team = [];

        current_users_block.find('div').each((k, e) => {
            team.push($(e).data('user'));
        });

        let {item, error} = await client.sendForm(`teams/${teamId}/users`, 'post', { team: team });

        if (error) return main.checkErrorStatus(error);

        await show(item.id);
    }

    return {
        initForm,
        show,
        deleteTeam,
        removeUser,
        sendTeamForm
    }
}
