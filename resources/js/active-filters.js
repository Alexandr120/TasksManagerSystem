export const ActiveFilters = () => {

    const render = list => {
        let block = $('<div id="active-filters" class="d-flex gap-2"></div>');

        let excludedFilters = ['page', 'per_page', 'direction'];

        Object.entries(list.filters)
            .filter(([filter, items]) => excludedFilters.indexOf(filter) === -1 && items.value)
            .forEach(([filter, value]) => {
                block.append(
                    '<div class="active-filter">' +
                        `<span>${list.listColumns[filter].title}</span>` +
                        `<span onclick="${list.name}.setFilter('${filter}', '')" class="remove-filter"><i class="bi bi-x"></i></span>` +
                    '</div>'
                )
            });

        $(`#${list.name}-list`).append(block);
    }

    return { render };
}
