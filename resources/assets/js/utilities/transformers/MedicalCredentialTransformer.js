import teamRoles from '../../data/team_roles.json';

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

        credential['files'] = 'n/a';

        if(this.document.uploads && this.document.uploads.length) {
            let files = _.map(this.document.uploads, function (upload) {
                return `<strong><a href="${upload.source}" target="_blank">${upload.name}</a></strong>`;
            });
            
            credential['files'] = files;
        }

        return credential;
    }
}

export default MedicalCredentialTransformer;