class MedicalCredentialTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
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
}

export default MedicalCredentialTransformer;