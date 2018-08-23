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

        return credential;
    }
}

export default MediaCredentialTransformer;