export const Auth = () => {

    let fields = {
        name: {
            type: 'text',
            title: 'Name',
            // required: true,
            validate_errors: [],
        },
        email: {
            type: 'email',
            title: 'Email',
            // required: true,
            validate_errors: [],
            login: true
        },
        password: {
            type: 'password',
            title: 'Password',
            // required: true,
            validate_errors: [],
            login: true
        },
        password_confirmation: {
            type: 'password',
            title: 'Confirm password',
            // required: true,
            validate_errors: []
        }
    }

    const renderForm = (method = 'register') => {
        if (method !== 'register') {
            fields = Object.fromEntries(
                Object.entries(fields).filter(([field, params]) => params.hasOwnProperty('login'))
            )
        }
        main.renderAuthForm(fields);
    }

    const send = async (method, event) => {
        event.preventDefault();

        let redirect = (method === 'register')? 'login' : 'home';

        $('.alert-danger').remove();

        let error = await client.auth(method, main.getAuthFormData(fields));

        if (error) {
            return main.renderAuthFormErrors(fields, error, method);
        } else {
            window.location.href = window.location.origin + '/' + redirect;
        }
    }

    return { renderForm, send };

}
