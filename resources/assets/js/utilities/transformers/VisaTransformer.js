class VisaTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
        return {
            id: this.document.id,
            name: this.document.given_names + ' ' + this.document.surname,
            number: this.document.number,
            country: this.document.country_name,
            expired: this.document.expired ? 'Yes' : 'No',
            issued: moment(this.document.issued_at).format('ll'),
            expiration: moment(this.document.expires_at).format('ll'),
            last_updated: moment(this.document.updated_at).format('ll')
        }
    }
}

export default VisaTransformer;