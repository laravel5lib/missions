class DocumentTransformer {
    constructor(document, type) {
        this.document = document;
        this.type = type;
    }

    get() {
        if (this.type == 'passports') {
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

        if (this.type == 'medical_releases') {
            return {
                id: this.document.id,
                name: this.document.name,
                last_updated: moment(this.document.updated).format('ll')
            }
        }

        throw "Unrecognized document type";
    }
}

export default DocumentTransformer;