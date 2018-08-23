import teamRoles from '../data/team_roles.json';

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

        if (this.type == 'essays' || this.type == 'influencer-applications') {
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

        if (this.type == 'medical_credentials') {
            let credential = {
                id: this.document.id,
                name: this.document.applicant_name,
                last_updated: moment(this.document.updated_at).format('ll')
            }

            let role = _.findWhere(this.document.content, {id: 'role'});
            credential[role.q] = _.findWhere(teamRoles, {value: role.a}).label;

            let certifications = _.findWhere(this.document.content, {id: 'certifications'});
            credential['Certifications'] = _.pluck(_.where(certifications.certifiedOptions, {value: true}), 'name');

            let participations = _.where(certifications.allOptions, {value: true});
            credential['Will Participate In'] = _.pluck(participations, 'name');

            _.each(this.document.content, function (item) {
                if (!item.id) credential[item.q] = item.a;
            });

            return credential;
        }

        if (this.type == 'media_credentials') {
            let credential = {
                id: this.document.id,
                name: this.document.applicant_name,
                last_updated: moment(this.document.updated_at).format('ll')
            }

            _.each(this.document.content, function (item) {
                
                let answer = item.a;

                if(item.id == 'role') {
                    answer = _.map(_.where(item.options, {value: true}), function (role) {
                        return role.name + ' (' + role.proficiency + ')';
                    });
                }

                if(item.id == 'equipment') {
                    answer = _.map(_.where(item.options, {value: true}), function (equipment) {
                        return equipment.name + ' (' + equipment.brand + ')';
                    });
                }

                credential[item.q] = answer;
            });

            return credential;
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