class MediaCredentialTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
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

export default MediaCredentialTransformer;