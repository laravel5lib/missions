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

        if (this.type == 'visas') {
            return {
                id: this.document.id,
                name: this.document.given_names + ' ' + this.document.surname,
                number: this.document.number,
                country: this.document.country_name,
                expired: this.document.expired ? 'Yes' : 'No',
                issued: moment(this.document.issued_at).format('ll'),
                expiration: moment(this.document.expires_at).format('ll'),
                last_updated: moment(this.document.updated).format('ll')
            }
        }

        if (this.type == 'essays') {
            let essay = {
                id: this.document.id,
                name: this.document.author_name,
                last_updated: moment(this.document.updated).format('ll')
            }

            _.each(this.document.content, function (item) {
                essay[item.q] = item.a;
            });

            return essay;
        }

        throw "Unrecognized document type";
    }
}

export default DocumentTransformer;