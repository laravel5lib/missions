<style lang="css" scoped>
</style>

<template>
    <component 
        v-bind:is="component"
        :id="id"
        :is-update="isUpdate" 
        :for-admin="forAdmin"
        :reservation-id="reservationId" 
        :requirement-id="requirementId"
    ></component>
</template>

<script>
import PassportCreateUpdate from './records/passports/passport-create-update.vue';
import VisaCreateUpdate from './records/visas/visa-create-update.vue';
import MedicalCreateUpdate from './records/medicals/medical-create-update.vue';
import MedicalCredentialCreateUpdate from './records/credentials/medical-credential-create-update.vue';
import MediaCredentialCreateUpdate from './records/credentials/media-credential-create-update.vue';
import EssayCreateUpdate from './records/essays/essay-create-update.vue';
import InfluencerQuestionnaireCreateUpdate from './records/influencers/influencer-questionnaire-create-update.vue';
import ReferralCreateUpdate from './records/referrals/referral-create-update.vue';
export default {

    name: 'TravelDocumentForm',

    props: {
        type: {
            type: String,
            required: true
        },
        id: String,
        isUpdate: {
            type: Boolean,
            default: false
        },
        forAdmin: {
            type: Boolean,
            default: false
        },
        reservationId: String,
        requirementId: String
    },

    components: {
        'passport-create-update': PassportCreateUpdate,
        'visa-create-update': VisaCreateUpdate,
        'medical-create-update': MedicalCreateUpdate,
        'medical-credential-create-update': MedicalCredentialCreateUpdate,
        'media-credential-create-update': MediaCredentialCreateUpdate,
        'essay-create-update': EssayCreateUpdate,
        'influencer-questionnaire-create-update': InfluencerQuestionnaireCreateUpdate,
        'referral-create-update': ReferralCreateUpdate
    },

    data () {
        return {
            components: {
                'passports': 'passport-create-update',
                'visas': 'visa-create-update',
                'medical-releases': 'medical-create-update',
                'medical-credentials': 'medical-credential-create-update',
                'media-credentials': 'media-credential-create-update',
                'essays': 'essay-create-update',
                'influencer-applications': 'influencer-questionnaire-create-update',
                'referrals': 'referral-create-update'
            }
        }
    },

    computed: {
        component() {
            return this.components[this.type];
        }
    }
}
</script>