class PassportTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
        return {
            id: this.document.id,
            name: this.document.given_names + ' ' + this.document.surname,
            number: this.document.number,
            nationality: this.document.birth_country_name,
            citizenship: this.document.citizenship_name,
            expiration: moment(this.document.expires_at).format('ll'),
            expired: this.document.expired ? 'Yes' : 'No'
        }
    }
}

export default PassportTransformer;