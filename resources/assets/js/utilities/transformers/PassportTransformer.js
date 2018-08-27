class PassportTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
        console.log(this.document);
        return {
            id: this.document.id,
            name: this.document.given_names + ' ' + this.document.surname,
            number: this.document.number,
            nationality: this.document.birth_country_name,
            citizenship: this.document.citizenship_name,
            expiration: moment(this.document.expires_at).format('ll'),
            expired: this.document.expired ? 'Yes' : 'No',
            file: this.document.upload ? `<strong><a href="${this.document.upload.source}" target="_blank">${this.document.upload.name}</a></strong>` : 'n/a',
            last_updated: moment(this.document.expires_at).format('ll'),
        }
    }
}

export default PassportTransformer;