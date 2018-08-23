import teamRoles from '../data/team_roles.json';
import PassportTransformer from './transformers/PassportTransformer';
import MedicalReleaseTransformer from './transformers/MedicalReleaseTransformer';
import VisaTransformer from './transformers/VisaTransformer';
import EssayTransformer from './transformers/EssayTransformer';
import MedicalCredentialTransformer from './transformers/MedicalCredentialTransformer';
import MediaCredentialTransformer from './transformers/MediaCredentialTransformer';
import ReferralTransformer from './transformers/ReferralTransformer';

class DocumentTransformer {
    constructor(document, type) {
        this.document = document;
        this.type = type;
    }

    get() {
        let types = {
            'passports': PassportTransformer,
            'medical_releases': MedicalReleaseTransformer,
            'visas': VisaTransformer,
            'essays': EssayTransformer,
            'influencer_applications': EssayTransformer,
            'medical_credentials': MedicalCredentialTransformer,
            'media_credentials': MediaCredentialTransformer,
            'referrals': ReferralTransformer
        }

        if ( ! types[this.type]) throw "Unrecognized document type";

        return new types[this.type](this.document).transform();
    }
}

export default DocumentTransformer;