class MedicalReleaseTransformer {
    constructor(document) {
        this.document = document;
    }

    transform() {
        let data = {
            id: this.document.id,
            last_updated: moment(this.document.updated_at).format('ll'),
            name: this.document.name,
            is_risk: this.document.is_risk ? 'Yes' : 'No',
            has_conditions: this.document.has_conditions ? 'Yes' : 'No',
            conditions: null,
            has_allergies: this.document.has_allergies ? 'Yes' : 'No',
            allergies: null,
            pregnant: this.document.pregnant ? 'Yes' : 'No',
            takes_medication: this.document.takes_medication ? 'Yes' : 'No',
            height: this.document.height,
            weight: this.document.weight,
            insurance_provider: this.document.ins_provider,
            insurance_policy_number: this.document.ins_policy_no,
            emergency_contact_name: this.document.emergency_contact.name,
            emergency_contact_relationship: this.document.emergency_contact.relationship,
            emergency_contact_email: this.document.emergency_contact.email,
            emergency_contact_phone: this.document.emergency_contact.phone
        }

        data['conditions'] = _.map(this.document.conditions, function (condition) {

            let diagnosed = condition.diagnosed ? ' (diagnosed) ' : '';
            let medication = condition.medication ? ' (takes medication) ' : '';

            return condition.name + diagnosed + medication;
        });


        data['allergies'] = _.map(this.document.allergies, function (allergy) {

            let diagnosed = allergy.diagnosed ? ' (diagnosed) ' : '';
            let medication = allergy.medication ? ' (takes medication) ' : '';

            return allergy.name + diagnosed + medication;
        });

        data['files'] = 'n/a';

        if(this.document.uploads && this.document.uploads.length) {
            let files = _.map(this.document.uploads, function (upload) {
                return `<strong><a href="${upload.source}" target="_blank">${upload.name}</a></strong>`;
            });
            
            data['files'] = files;
        }

        return data;
    }
}

export default MedicalReleaseTransformer;