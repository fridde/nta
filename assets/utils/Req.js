'use strict';

class Req {

    constructor() {
    }

    url = '';
    method = 'POST';
    data = {};

    successHandler = (data) => console.log(data);
    errorHandler = (err) => console.log(err);


    addToData = (key, value) => {
        this.data[key] = value;

        return this;
    }

    setUrl = (url) => {
        this.url = url;
        return this;
    };

    setMethod = (method) => {
        this.method = method.toUpperCase();
        return this;
    };

    setSuccessHandler = (successHandler) => {
        this.successHandler = successHandler;
        return this;
    }

    setErrorHandler = (errorHandler) => {
        this.errorHandler = errorHandler;
        return this;
    }

    static create = () => {
        return new this();
    }

    send() {
        const options = {};

        options.method = this.method;
        if(this.method === 'POST'){
            options.body = JSON.stringify(this.data);
            options.headers = {"Content-type": "application/json"};
        }

        return fetch(this.url, options)
            .then(response => {
                if(response.ok){
                    return response.json();
                }
                return response.text().then(text => {throw new Error(text)});
            })
            .then(this.successHandler)
            .catch(this.errorHandler);
    };
}

export default Req;