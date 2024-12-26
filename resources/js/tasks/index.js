export const TasksList = () => {

    const status_colors = {
        1: 'pending',
        2: 'in_progress',
        3: 'completed',
    }

    const name = 'tasks';

    const listColumns = {
        id: { size: 'col-sm-auto', title: 'Id', filter_type: 'direction' },
        status: { size: 'col-sm-1', title: 'Status', filter_type: 'select' },
        title: { size: 'col-sm text-start', title: 'Title', filter_type: 'search' },
        team: { size: 'col-sm-2', title: 'Team', filter_type: 'select' },
        user: { size: 'col-sm-2', title: 'User', filter_type: 'search' },
        created_at: { size: 'col-sm-1', title: 'Created at', filter_type: 'date' },
        updated_at: { size: 'col-sm-1', title: 'Updated at', filter_type: 'date' },
        actions: { size: 'col-sm-1', title: 'Actions' }
    };

    const filters = {
        page: { value: 1 },
        per_page: { value: 20 },
        direction: { value: 'desc' },
        status: { value: '', options: {} },
        title: { value: ''},
        // team: { value: '', options: {} },
        // user: { value: '', options: {} },
        created_at: { value: '' },
        updated_at: { value: '' }
    };

    const setFilter = async (filter, value) => {
        console.log(filter, value)
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

        $('#tasks-list').html('');

        paginate.render(tasks, pagination);
        activeFilters.render(tasks);

        if (Object.keys(filters_options).length > 0) {
            Object.entries(filters_options).forEach(([filter, options]) => {
                if (!filters.hasOwnProperty(filter)) {
                    filters[filter] = {value: '', options: {}};
                }
                filters[filter].options = options;
            });
        }

        appendListHeaders();
        appendListFilters();
        appendList(list);
    }

    const appendListHeaders = () => {
        let list_header = $('<div class="row task-list-header"/>');

        Object.entries(listColumns).forEach(([key, item]) => {
            let title = item.title;
            if (key === 'user' && filters.hasOwnProperty(key)) {
                title = '<div class="text-center">' +
                    `<div>${item.title}</div>` +
                        `<div><small onclick="tasks.setFilter('user', 0)" class="text-primary pointer">Show tasks without user</small></div>` +
                    '</div>'
            }
            list_header.append(`<div class="${item.size}">${title}</div>`);
        });

        $('#tasks-list').append(list_header);
    }

    const appendListFilters = () => {
        let direction = 'asc';
        let icon = 'down';
        if (filters.direction.value === 'asc') {
            direction = 'desc';
            icon = 'up';
        }

        let list_filters = $('<div class="row task-list-filters"/>');

        let directionItem = $(`<a onclick="tasks.setFilter('direction', '${direction}')" id="list-direction" class="text-decoration-none pointer"/>`);
        directionItem.append(`<i class="bi bi-arrow-${icon}"></i>`);

        list_filters.append($(`<div class="${listColumns.id.size}">`).append(directionItem));

        Object.entries(listColumns).filter(([key, item]) => key !== 'id')
            .forEach(([key, item]) => {
                let block = $(`<div class="${item.size}">`);
                let filter = '';
                if (item.hasOwnProperty('filter_type') && filters.hasOwnProperty(key)) {
                    switch (item.filter_type) {
                        case 'select':
                            filter = $(`<select onchange="tasks.setFilter('${key}', this.value)" id="${key}-filter" class="form-select form-select-sm border-dark-subtle">`);
                            let _filters = filters[key].options;
                            if (key === 'team') {
                                _filters  = {...{0:'Without team'}, ...filters[key].options}
                            }
                            main.addSelectOptions(filter, _filters, filters[key].value);
                            break;
                        default :
                            filter = $(`<input onchange="tasks.setFilter('${key}', this.value)" type="${item.filter_type}" id="${key}-filter" class="form-control form-control-sm border-dark-subtle" value="${filters[key].value}">`);
                    }
                }

                list_filters.append(block.append(filter));
            });

        $('.task-list-header').after(list_filters);
    }

    const appendList = tasks => {
        if (tasks.length > 0) {
            let user = main.getUser();

            tasks.forEach(task => {
                let row = $(`<div class="row task ${status_colors[task.status_id]} text-center"/>`);
                Object.entries(listColumns).filter(([key, item]) => key !== 'actions').forEach(([k, i]) => {
                    row.append(`<div class="${i.size}">${task[k]}</div>`);
                });

                let editBtnAction = (user.role === 'manager')? `task.show(${task.id})` : `task.showUserTask(${task.id})`;

                row.append(
                    `<div class="d-flex justify-content-center gap-2 ${listColumns.actions.size}">` +
                        `<button onclick="${editBtnAction}" type="button" class="btn btn-sm btn-secondary" title="Edit"><i class="bi bi-pencil-square"></i></button>` +
                        `<button onclick="task.deleteTask(${task.id})" type="button" class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>` +
                    '</div>'
                );

                $('#tasks-list').append(row);
            });
        }
    }

    return { name, listColumns, filters, setFilter, render };
}
