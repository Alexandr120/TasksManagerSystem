export const Paginate = () => {

    const listRows = {'10':'10', '20':'20', '50':'50'};

    const render = (list, pagination) => {
        let block = $(`#${list.name}-list`);
        let label = list.name.replace(/^\w/, c => c.toUpperCase()) + ' count';
        let count_selector_id = `${list.name}-rows-count`;

        block.append(
            '<div class="d-flex justify-content-between">' +
                '<div class="row align-items-center">' +
                    '<div class="col-sm-auto">' +
                        `<label for="rows-count">${label}</label>` +
                    '</div>' +
                    '<div class="col-sm-auto">' +
                        `<select onchange="${list.name}.setFilter('per_page', this.value)" id="${count_selector_id}" class="form-select form-select-sm"></select>` +
                    '</div>' +
                '</div>' +
                '<nav aria-label="Page navigation">' +
                    `<ul class="pagination" id="${list.name}-pagination-controls"></ul>` +
                '</nav>' +
            '</div>'
        );

        main.addSelectOptions($(`#${count_selector_id}`), listRows, list.filters.per_page.value, false);

        setPagination(list, pagination);
    }

    const setPagination = (list, pagination) => {
        let paginationControls = $(`#${list.name}-pagination-controls`);

        paginationControls.append(
            '<div class="d-flex align-items-center pe-3">' +
                `<small>${pagination.per_page} from ${pagination.total}</small>` +
            '</div>'
        );

        let currentPage = parseInt(list.filters.page.value);

        let previousLink = $('<li/>').attr({ class: (currentPage === 1)? 'page-item disabled' : 'page-item'});

        let previous = $('<a class="page-link" aria-label="Previous">Previous</a>');

        previous.click(async () => {
            let previousPage = currentPage - 1;
            if (previousPage !== 0) await list.setFilter('page', previousPage);
        });
        paginationControls.append(previousLink.append(previous));

        for (let page = 1; page <= pagination.last_page; page++) {
            let li = $('<li/>').attr({ class: (page === currentPage)? 'page-item active' : 'page-item'});
            paginationControls.append(li.append(
                `<a onclick="${list.name}.setFilter('page', ${page})" class="page-link">${page}</a>`
            ));
        }

        let nextLink = $('<li/>').attr({ class: (currentPage === pagination.last_page)? 'page-item disabled' : 'page-item'});
        let next = $('<a class="page-link" aria-label="Next">Next</a>');
        next.click(async () => {
            let nexPage = currentPage + 1;
            if (nexPage <= pagination.last_page) await list.setFilter('page', nexPage);

        });
        paginationControls.append(nextLink.append(next));
    }

    return { render };
}
