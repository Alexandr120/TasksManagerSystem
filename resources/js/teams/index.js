export const TeamsList = () => {

    const name = 'teams';

    const listColumns =  {
        id: {size: "col-sm-1", title: "Id", filter_type: 'direction'},
        name: {size: "col-sm-5", title: "Name", filter_type: 'search'},
        created_at: {size: "col-sm-2", title: "Created at", filter_type: 'date'},
        updated_at: {size: "col-sm-2", title: "Updated at", filter_type: 'date'},
        actions: {size: "col-sm-2", title: "Actions"}
    }

    const filters = {
        page: { value: 1 },
        per_page: { value: 20 },
        direction: { value: 'desc' },
        name: { value: '' },
        created_at: { value: '' },
        updated_at: { value: '' },
    };

    const teams_users = {};

    const setFilter = async (filter, value) => {
        filters[filter].value = value;
        await render();
    }

    const getFiltersValue = () => {
        return Object.keys(filters).reduce((acc, key) => {
            acc[key] = filters[key].value;
            return acc;
        }, {});
    }

    const render = async ()  => {
        let {list, filters_options, pagination, error} = await client.getList(name, getFiltersValue());

        if (error) return main.checkErrorStatus(error);

        $('#teams-list').html('');

        paginate.render(teams, pagination);
        activeFilters.render(teams);

        if (Object.keys(filters_options).length > 0) {
            Object.entries(filters_options).forEach(([filter, options]) => {
                if (filters.hasOwnProperty(filter)) {
                    filters[filter].options = options;
                }
            });
        }

        appendListHeader();
        appendListFilters();
        appendList(list);
    }

    const appendListHeader = () => {
        let list_header = $('<div class="row team-list-header"/>');

        Object.entries(listColumns).forEach(([key, item]) => {
            list_header.append(`<div class="${item.size}">${item.title}</div>`);
        });

        $('#teams-list').append(list_header);
    }

    const appendListFilters = () => {
        let direction = 'asc';
        let icon = 'down';
        if (filters.direction.value === 'asc') {
            direction = 'desc';
            icon = 'up';
        }

        let list_filters = $('<div class="row team-list-filters"/>');

        let directionItem = $(`<a onclick="teams.setFilter('direction', '${direction}')" id="list-direction" class="text-decoration-none pointer"/>`);
        directionItem.append(`<i class="bi bi-arrow-${icon}"></i>`);

        list_filters.append($(`<div class="${listColumns.id.size}">`).append(directionItem));

        Object.entries(listColumns).filter(([key, item]) => key !== 'id')
            .forEach(([key, item]) => {
                let block = $(`<div class="${item.size}">`);
                let filter = '';
                if (item.hasOwnProperty('filter_type')) {
                    switch (item.filter_type) {
                        case 'select':
                            filter = $(`<select onchange="teams.setFilter('${key}', this.value)" id="${key}-filter" class="form-select form-select-sm border-dark-subtle">`);
                            main.addSelectOptions(filter, filters[key].options, filters[key].value);

                            break;
                        default :
                            filter = $(`<input onchange="teams.setFilter('${key}', this.value)" type="${item.filter_type}" id="${key}-filter" class="form-control form-control-sm border-dark-subtle" value="${filters[key].value}">`);
                    }
                }

                list_filters.append(block.append(filter));
            });

        $('.team-list-header').after(list_filters);
    }

    const appendList = teams => {
        if (teams.length > 0) {
            let teamContentBlock = $('#teams-list');

            teams.forEach(team => {
                let row = $('<div class="row team"/>');
                Object.entries(listColumns).filter(([key, item]) => key !== 'actions').forEach(([k, i]) => {
                    row.append(`<div class="${i.size} text-center">${team[k]}</div>`);
                });

                row.append(
                    `<div class="d-flex justify-content-center gap-2 ${listColumns.actions.size}">` +
                        '<button type="button" class="btn btn-sm btn-primary" title="Users" ' +
                                `data-bs-toggle="collapse" data-bs-target="#team-users-${team.id}" aria-expanded="false" aria-controls="team-users-${team.id}">` +
                            '<i class="bi bi-people"></i>' +
                        '</button>' +
                        `<button onclick="team.show(${team.id})" type="button" class="btn btn-sm btn-secondary" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
                        `<button onclick="team.deleteTeam(${team.id})" type="button" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>` +
                    '</div>'
                );

                teamContentBlock.append(row);

                teams_users[team.id] = team.users;

                let usersList = $(`<div class="collapse team-users" id="team-users-${team.id}"/>`);

                Object.entries(team.users).forEach(([id, user]) => {
                    usersList.append(
                        '<div class="row ms-3">' +
                            '<div class="col-sm-auto">' +
                                `<span onclick="team.removeUser(${team.id}, ${id})" class="text-danger pointer">` +
                                    '<i class="bi bi-x-circle"></i>' +
                                '</span>' +
                            '</div>' +
                            `<div class="col-sm-3">${user}</div>` +
                        '</div>'
                    );
                });

                teamContentBlock.append(usersList);
            });
        }
    }

    return { name, listColumns, filters, teams_users, render, setFilter };
}
