class ReferralTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
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

        if (document.location.pathname.split("/").slice(1, 2).toString() == 'admin') {
            _.each(this.document.response, function (item) {
                referral[item.q] = item.a;
            });
        }

        return referral;
    }
}

export default ReferralTransformer;