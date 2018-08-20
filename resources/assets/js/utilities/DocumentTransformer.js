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
                last_updated: moment(this.document.updated_at).format('ll')
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
                last_updated: moment(this.document.updated_at).format('ll')
            }
        }

        if (this.type == 'essays' || 'influencer-applications') {
            let essay = {
                id: this.document.id,
                name: this.document.author_name,
                last_updated: moment(this.document.updated_at).format('ll')
            }

            _.each(this.document.content, function (item) {
                essay[item.q] = item.a;
            });

            return essay;
        }

        if (this.type == 'referrals') {
            let referral = {
                id: this.document.id,
                name: this.document.applicant_name,
                attention: this.document.attention_to,
                recipient_email: this.document.recipient_email,
                sent: moment(this.document.sent_at).format('ll'),
                replied: this.document.responded_at ? moment(this.document.responded_at).format('ll') : null,
                last_updated: moment(this.document.updated_at).format('ll')
            }

            referral['referrer_name'] = this.document.referrer.name;
            referral['referrer_title'] = this.document.referrer.title;
            referral['referrer_phone'] = this.document.referrer.phone;
            referral['referrer_organization'] = this.document.referrer.organization;

            _.each(this.document.response, function (item) {
                referral[item.q] = item.a;
            });

            return referral;
        }

        throw "Unrecognized document type";
    }
}

export default DocumentTransformer;