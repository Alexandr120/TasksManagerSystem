export const Main = () => {

    const addSelectOptions = (selector, options, needle, addEmpty = true) => {
        if (addEmpty) selector.append('<option/>');

        Object.entries(options).forEach(([value, label]) => {
            let option = $('<option/>');
            option.attr({value: value, selected: selectedIfThis(needle, value)});
            option.text(label);
            selector.append(option);
        });
    }

    const selectedIfThis = (needle, value) => {
        return parseInt(needle) === parseInt(value);
    }

    const openTab = tab => {
        let tabContent = $('#pills-tabContent');
        let menu = $('#header-menu');

        tabContent.find('.tab-pane').removeClass('show active');
        menu.find('.custom-nav-link').removeClass('active');

        menu.find(`#${tab}-tab`).addClass('active');
        tabContent.find(`#${tab}`).addClass('show active');
    }

    const getUser = () => {
        return JSON.parse(localStorage.getItem('user'));
    }

    const getUserToken = () => {
        return getUser()?.token;
    }

    const checkUserRole = () => {
        if (getUser().role !== 'manager') {
            let ignoreBlocks = ['pills-edit-team-tab', 'pills-edit-team'];
            ignoreBlocks.forEach(block => $(`#${block}`).remove());
        }
    }

    const showAlert = (style, message) => {
        main.resetAlertMessage();
        $('.container').prepend(
            `<div id="alert-message" class="d-flex justify-content-between alert alert-${style}">` +
                `<span>${message}</span>` +
                `<button onclick="main.resetAlertMessage()" type="button" class="btn-close" aria-label="Close"></button>` +
            '</div>'
        );
    }

    const resetAlertMessage = () => {
        $('#alert-message').remove();
    }

    const removeValidateError = selector => {
        if(selector.hasClass('isInvalid')) {
            selector.removeClass('isInvalid');
            $(`#form-group-${selector.attr('name')}`).find('.text-danger').remove();
        }
    }

    const renderAuthForm = fields => {
        let form = $('.authorize-fields');
        form.html('');

        for (let [field, params] of Object.entries(fields)) {
            let input_group = $(`<div/>`);
            input_group.attr({
                id: `form-group-${field}`,
                class: "d-flex flex-column"
            });

            let input = $('<input/>');
            input.attr({
                type: params.type,
                name: field,
                id: field,
                class: "form-control",
                placeholder: params.title,
                required: (params.hasOwnProperty('required'))
            });

            input.change(e => removeValidateError($(e.target)));

            form.append(input_group.append(input));

            if (params.validate_errors.length > 0) {
                input.addClass('isInvalid');
                params.validate_errors.forEach(error => {
                    input_group.append(`<span class="text-danger">${error}</span>`);
                });
            }
        }
    }

    const getAuthFormData = fields => {
        let obj = {};

        for (let [field, params] of Object.entries(fields)) {
            obj[field] = $(`#${field}`).val();
        }

        return obj;
    }

    const renderAuthFormErrors = (fields, error, method) => {
        let message = null;

        switch (error.status) {
            case 422 :
                Object.entries(error.response.data.errors).forEach(([name, errors]) => {
                    fields[name].validate_errors = errors;
                });
                auth.renderForm(method);

                break;
            case 400 : message = error.response.data.message;
                break;
            case 500 : message = '500 | SERVER ERROR!';
                break;
            default : console.log(error);
        }

        if (message) {
            $('.authorize-block').append(
                '<div class="alert alert-danger">' +
                    `<span class="text-danger">${message}</span>` +
                '</div>'
            );
        }
    }

    const checkErrorStatus = error => {
        switch (error.status) {
            case 403 :
                showAlert('danger', "403 | Forbidden !");
                break;
            case 401 :
                localStorage.removeItem('user');
                window.location.href = window.location.origin + '/login';
                break;
            case 500:
                showAlert('danger', "500 | Server Error !");
                console.log(error);
                break;
            default : console.log(error);
        }

        return null;
    }

    return {
        getUser, getUserToken, checkUserRole,
        renderAuthForm, getAuthFormData, renderAuthFormErrors,
        removeValidateError,
        addSelectOptions,
        openTab,
        showAlert, resetAlertMessage,
        checkErrorStatus
    }
}









