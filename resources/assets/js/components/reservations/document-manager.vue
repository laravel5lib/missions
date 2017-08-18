<template>
    <section>
    <div class="row" v-if="loaded" style="position:relative">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="col-sm-12 tour-step-attach">
            <div class="text-center" v-if="list && ! isLocked">
                <form novalidate>
                    <a class="btn btn-default-hollow btn-sm" @click="toggleChangeState()">
                        <span v-if="!changeState">
                            <i class="fa fa-cog icon-left"></i> Manage
                        </span>
                        <span v-else>
                            <i class="fa fa-times"></i> Cancel
                        </span>
                    </a>
                    <a class="btn btn-primary-hollow btn-sm" :href="'/' + firstUrlSegment + '/records/' + url + '/create'"><i class="fa fa-plus icon-left"></i> Create New</a>
                    <a class="btn btn-default-hollow btn-sm" @click="removeDocument(document)" v-if="document">
                        <i class="fa fa-trash icon-left"></i> Remove
                    </a>
                </form>
                <hr class="divider inv">
            </div>
            <div class="panel panel-default" v-if="document && !changeState && !questionnaire" transition="fade">
                <div class="panel-body">
                    <component :is="docPreview"
                               :document="document"
                               @set-decument="setDocument"
                               @unset-decument="removeDocument"
                               :locked="isLocked"
                               keep-alive>
                    </component>
                </div><!-- end panel-body -->
            </div>
            <div v-if="questionnaire">
                <div>
                    <component :is="questionnaire"
                               :document="document"
                               :reservation-id="requirement.reservation_id"
                               :locked="isLocked"
                               @set-decument="setDocument"
                               @unset-decument="removeDocument"
                               keep-alive>
                    </component>
                </div>
            </div>
            <div v-if="!document && list" role="alert"><p class="text-muted text-center">
                <em>This requirement has no {{ label.toLowerCase() }}(s) assigned to it. Please select one or add one.</em>
            </p></div>
        </div>

        <div class="col-sm-12" v-if="changeState && list" transition="fade">
            <div class="row">
                <div class="col-sm-12">
                    <component :is="list"
                               :user-id="managingUserId"
                               @set-decument="setDocument"
                               @unset-decument="removeDocument"
                               selector keep-alive>
                    </component>
                </div>
            </div>
        </div>
    </div>
    </section>
</template>
<script>
    import passportsList from '../records/passports/passports-list.vue';
    import passport from '../records/passports/passport.vue';
    import visasList from '../records/visas/visas-list.vue';
    import visa from '../records/visas/visa.vue';
    import essaysList from '../records/essays/essays-list.vue';
    import influencerQuestionnairesList from '../records/influencers/influencer-questionnaires-list.vue';
    import influencerQuestionnaire from '../records/influencers/influencer-questionnaire.vue';
    import essay from '../records/essays/essay.vue';
    import medicalsList from '../records/medicals/medicals-list.vue';
    import medical from '../records/medicals/medical.vue';
    import travelItineraries from '../reservations/travel-itineraries.vue';
    import medicalCredentialsList from '../records/credentials/medical-credentials-list.vue';
    import medicalCredential from '../records/credentials/medical-credential.vue';
    import mediaCredentialsList from '../records/credentials/media-credentials-list.vue';
    import mediaCredential from '../records/credentials/media-credential.vue';
    import arrivalDesignation from '../reservations/arrival-designation.vue';
    import airportPreference from '../reservations/airport-preference.vue';
    import referralsList from '../records/referrals/referrals-list.vue';
    import minorRelease from '../reservations/minor-release.vue';
    import referral from '../records/referrals/referral.vue';
    export default{
        name: 'document-manager',
        components: {
            passportsList,
            passport,
            visasList,
            visa,
            essaysList,
            influencerQuestionnairesList,
            influencerQuestionnaire,
            essay,
            medicalsList,
            medical,
            referralsList,
            referral,
            arrivalDesignation,
            travelItineraries,
            airportPreference,
            minorRelease,
            medicalCredentialsList,
            medicalCredential,
            mediaCredentialsList,
            mediaCredential
        },
        props:{
            'reservationId': {
                type: String,
                required: true
            },
            'requirementId': {
                type: String,
                required: true
            },
            'userId': {
                type: String,
                required: false
            },
            'locked': {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                document: null,
                documents: [],
                requirement: {},
                //logic vars
                requirementResource: this.$resource('reservations/{reservationId}/requirements/{requirementId}'),
                loaded: false,
                newState: false,
                changeState: false,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                label: '',
                url: null,
                list: null,
                docPreview: null,
                questionnaire: null
            }
        },
        computed: {
            'managingUserId': function() {
                if (this.userId) return this.userId;

                return this.$root.user.id;
            },
            'isLocked': function() {
                if (this.isAdminRoute)
                    return false;

                return this.locked;
            }
        },
        watch:{
            'search'(val, oldVal) {
                this.page = 1;
                this.fetch();
            },
            'page'(val, oldVal) {
                this.fetch();
            },
            'per_page'(val, oldVal) {
                this.fetch();
            },
            'requirement.document_type'(val, oldVal) {
                switch(val) {
                    case 'passports':
                        this.label = 'Passport';
                        this.url = 'passports';
                        this.list = 'passports-list';
                        this.docPreview = 'passport';
                        break;
                    case 'visas':
                        this.label = 'Visa';
                        this.url = 'visas';
                        this.list = 'visas-list';
                        this.docPreview = 'visa';
                        break;
                    case 'essays':
                        this.label = 'Essay';
                        this.url = 'essays';
                        this.list = 'essays-list';
                        this.docPreview = 'essay';
                        break;
                    case 'influencer_applications':
                        this.label = 'Influencer Application';
                        this.url = 'influencers';
                        this.list = 'influencer-questionnaires-list';
                        this.docPreview = 'influencer-questionnaire';
                        break;
                    case 'medical_releases':
                        this.label = 'Medical Release';
                        this.url = 'medical-releases';
                        this.list = 'medicals-list';
                        this.docPreview = 'medical';
                        break;
                    case 'referrals':
                        this.label = 'Referral';
                        this.url = 'referrals';
                        this.list = 'referrals-list';
                        this.docPreview = 'referral';
                        break;
                    case 'medical_credentials':
                        this.label = 'Medical Credentials';
                        this.url = 'medical-credentials';
                        this.list = 'medical-credentials-list';
                        this.docPreview = 'medical-credential';
                        break;
                    case 'media_credentials':
                        this.label = 'Media Credentials';
                        this.url = 'media-credentials';
                        this.list = 'media-credentials-list';
                        this.docPreview = 'media-credential';
                        break;
                    case 'airport_preferences':
                        this.questionnaire = 'airport-preference';
                        break;
                    case 'arrival_designations':
                        this.questionnaire = 'arrival-designation';
                        break;
                    case 'travel_itineraries':
                        this.questionnaire = 'travel-itineraries';
                        break;
                    case 'minor_releases':
                        this.questionnaire = 'minor-release';
                        break;
                    default:
                        this.label = 'Document';
                        this.url = null;
                        this.list = null;
                        this.docPreview = null;
                }
            }
        },
        methods:{
            isAdmin() {
                return this.firstUrlSegment == 'admin';
            },
            setDocument(document){
                if(document) {
                    this.document = document;
                    this.requirementResource.put({
                        reservationId: this.reservationId,
                        requirementId: this.requirementId},
                    {
                        document_id: document.id,
                        status: 'reviewing'
                    }).then((response) => {
                        this.toggleChangeState();
                        this.$emit('set-status', response.data.data);
                    });
                }
            },
            removeDocument(document){
                if(document) {
                    this.requirementResource.put({
                        reservationId: this.reservationId,
                        requirementId: this.requirementId},
                    {
                        document_id: null,
                        status: 'incomplete'
                    }).then((response) => {
                        this.document = null;
                        this.$emit('set-status', response.data.data);
                    });
                }
            },
            toggleChangeState(){
                this.changeState=!this.changeState;
                this.newState = false;
            },
            toggleNewState(){
                this.newState=!this.newState;
                this.changeState = false;
            },
            fetch() {
                var params = {
                    user: this.$root.user.id,
                    manager: this.$root.user.id,
                };
                this.requirementResource.get({
                    reservationId: this.reservationId,
                    requirementId: this.requirementId,
                    include: 'document'
                }).then((response) => {
                    this.requirement = response.data.data;
                    this.document = response.data.data.document ? response.data.data.document.data : null;
                    this.loaded = true;
                });
            },
        },
        /*events: {
            'set-document': function(document) {
                this.setDocument(document);
            },
            'unset-document': function(document) {
                this.removeDocument(document);
            }
        },*/
        mounted(){
            this.fetch();
        }
    }
</script>
