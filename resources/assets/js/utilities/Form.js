import Errors from './Errors';

class Form {
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }

    reset() {
        for (let field in this.originalData) {
            this[field] = this.originalData[field];
        }

        this.errors.clear();
    }

    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }

    set(field, value) {
        this.originalData[field] = value;
    }

    get(url) {
        return this.submit('get', url);
    }

    post(url) {
        return this.submit('post', url);
    }

    put(url) {
        return this.submit('put', url);
    }

    patch(url) {
        return this.submit('patch', url);
    }

    delete(url) {
        return this.submit('delete', url);
    }

    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error.response.data.errors);

                    reject(error.response.data.errors);
                });
        });
    }

    onFail(errors) {
        this.errors.record(errors)
    }
}

export default Form;