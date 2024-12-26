import axios from "axios";

export const Client = () => {

    let dataTypes = {
        get: 'params',
        post: 'data',
        delete: 'data',
        put: 'data',
    }

    const auth = async (url, form = {}) => {
        let error = null;

        await axios.request(getConfig(url, 'post', form, (url === 'logout')))
            .then(res => {
                switch (url) {
                    case 'login':
                        localStorage.setItem('user', JSON.stringify({
                            'name': res.data.user,
                            'role': res.data.role,
                            'token': res.data.authorisation.token
                        }));
                        break;
                    case 'register': sessionStorage.setItem('register', 'OK');
                        break;
                    case 'logout':
                        localStorage.removeItem('user');
                        window.location.href = window.location.origin + '/login';
                        break;
                    default: //
                }

            })
            .catch(err => error = err);

        return error;
    }

    const getList = async (listName, filters) => {
        let result = {
            list: null,
            filters_options: {},
            pagination: null,
            error: null
        }

        let page = filters.page ?? 1;

        await axios.request(getConfig(`${listName}?page=${page}`, 'get', filters))
            .then(res => {
                result.list = res.data.list;
                if (typeof res.data.filters_options !== 'undefined') {
                    result.filters_options = res.data.filters_options;
                }
                result.pagination = res.data.pagination;

            }).catch(err => {
                console.log(err);
                result.error = err;
            });

        return result;
    }

    const getListItem = async (listName, itemId) => {
        let result = {
            item: null,
            error: null
        }

        await axios.request(getConfig(`${listName}/${itemId}`, 'get'))
            .then(res => result.item = res.data.item)
            .catch(err => result.error = err );

        return result;
    }

    const sendForm = async (url, method, form= {}) => {
        let result = {
            item: null,
            error: null
        }

        await axios.request(getConfig(url, method, form))
            .then(res => {
                if (res.data.hasOwnProperty('item')) {
                    result.item = res.data.item;
                }
            })
            .catch(err => result.error = err )

        return result;
    }

    const getConfig = (url, method, data = {}, auth = true) => {
        let headers = {
            "Content-Type": 'application/json',
            Authorization: `Bearer ${main.getUserToken()}`
        }

        if (!auth) headers = Object.entries(headers).filter(([key, item]) => key !== 'Authorization');

        let config = {
            method: method,
            baseURL: window.location.origin + '/api/' + url,
            headers: headers
        };

        config[dataTypes[method]] = data;

        return config;
    }

    return {
        getConfig,
        auth,
        getList,
        getListItem,
        sendForm
    }
}













