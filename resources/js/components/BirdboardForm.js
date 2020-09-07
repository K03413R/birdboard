class BirdboardForm {
    constructor(data) {
        this.originalData = JSON.parse(JSON.stringify(data));
        Object.assign(this, data);
        this.errors = {};
    }

    data(){
        let data = {};

        for(let attribute in this.originalData){
            data[attribute] = this[attribute];
        }

        return data
    }

    submit(endpoint){
        return axios.post(endpoint, this.data())
            .catch(this.onFail.bind(this))
            .then(this.onSuccess.bind(this));
    }

    onFail(error){
        this.errors = error.response.data.errors;
        throw error;
    }

    onSuccess(response){
        this.errors = {};
        return response;
    }
}
export default BirdboardForm;
