/**
 * Here is where you can register global vue components.
 */

/**
 * Frontend Forms
 */

Vue.component('contact-form', require('./components/contact-form.vue'));
Vue.component('speaker-form', require('./components/speaker-form.vue'));
Vue.component('sponsor-project-form', require('./components/sponsor-project-form.vue'));

/**
 * Donation, Funds, and Transaction Components
 */
Vue.component('donate', require('./components/donate.vue'));
Vue.component('modal-donate', require('./components/modal-donate.vue'));
Vue.component('donations-list', require('./components/reservations/donations-list.vue'));
Vue.component('reconcile-fund', require('./components/reconcile-fund.vue'));
Vue.component('admin-donors-list', require('./components/financials/donors/admin-donors-list.vue'));
Vue.component('admin-funds-list', require('./components/financials/funds/admin-funds-list.vue'));
Vue.component('admin-transactions-list', require('./components/financials/transactions/admin-transactions-list.vue'));
Vue.component('donor-form', require('./components/financials/donors/donor-form.vue'));
Vue.component('refund-form', require('./components/financials/transactions/refund-form.vue'));
Vue.component('transaction-delete', require('./components/financials/transactions/transaction-delete.vue'));
Vue.component('fund-manager', require('./components/financials/funds/fund-manager.vue'));
Vue.component('restore-fund', require('./components/financials/funds/restore-fund.vue'));

/**
 * Fundraiser Components
 */
Vue.component('fundraisers', require('./components/fundraisers/fundraisers.vue'));
Vue.component('fundraisers-manager', require('./components/fundraisers/fundraisers-manager.vue'));
Vue.component('fundraisers-stories', require('./components/fundraisers/fundraisers-stories.vue'));
Vue.component('fundraisers-uploads', require('./components/fundraisers/fundraisers-uploads.vue'));
Vue.component('fundraiser-collection', require('./components/fundraisers/fundraiser-collection.vue'));


/**
 * Group components
 */
Vue.component('groups', require('./components/groups/groups.vue'));
Vue.component('groups-list', require('./components/groups/groups-list.vue'));
Vue.component('group-trips', require('./components/campaigns/group-trips.vue'));
Vue.component('group-profile-trips', require('./components/groups/group-profile-trips.vue'));
Vue.component('group-profile-stories', require('./components/groups/group-profile-stories.vue'));
Vue.component('group-trip-wrapper', require('./components/campaigns/groups-trips-selection-wrapper.vue'));
Vue.component('group-interest-signup', require('./components/groups/group-interest-signup.vue'));
Vue.component('group-profile-fundraisers', require('./components/groups/group-profile-fundraisers.vue'));
Vue.component('dashboard-group-trips', require('./components/groups/dashboard-group-trips.vue'));
Vue.component('dashboard-group-reservations', require('./components/groups/dashboard-group-reservations.vue'));
Vue.component('dashboard-interests-list', require('./components/interests/dashboard-interests-list.vue'));
Vue.component('admin-group-trips', require('./components/groups/admin-group-trips-list.vue'));
Vue.component('admin-groups', require('./components/groups/admin-groups-list.vue'));
Vue.component('admin-group-create', require('./components/groups/admin-group-create.vue'));
Vue.component('admin-group-edit', require('./components/groups/admin-group-edit.vue'));
Vue.component('admin-group-delete', require('./components/groups/admin-group-delete.vue'));
Vue.component('admin-group-managers', require('./components/groups/admin-group-managers.vue'));

/**
 * Campaign and Trip Components
 */
Vue.component('campaigns', require('./components/campaigns/campaigns.vue'));
Vue.component('campaign-groups', require('./components/campaigns/campaign-groups.vue'));
Vue.component('visibility-controls', require('./components/campaigns/visibility-controls.vue'));
Vue.component('trip-details-missionaries', require('./components/trips/trip-details-missionaries.vue'));
Vue.component('trip-registration-wizard', require('./components/trips/trip-registration-wizard.vue'));
Vue.component('campaign-create', require('./components/campaigns/admin-campaign-create.vue'));
Vue.component('campaign-edit', require('./components/campaigns/admin-campaign-edit.vue'));
Vue.component('admin-campaigns-list', require('./components/campaigns/admin-campaigns-list.vue'));
Vue.component('admin-campaign-details', require('./components/campaigns/admin-campaign-details.vue'));
Vue.component('campaign-trip-create-wizard', require('./components/trips/admin-trip-create.vue'));
Vue.component('campaign-trip-edit-wizard', require('./components/trips/admin-trip-edit.vue'));
Vue.component('admin-campaign-trips', require('./components/campaigns/admin-campaign-trips.vue'));
Vue.component('admin-trip-reservations', require('./components/trips/admin-trip-reservations-list.vue'));
Vue.component('admin-trip-facilitators', require('./components/trips/admin-trip-facilitators.vue'));
Vue.component('admin-trip-duplicate', require('./components/trips/admin-trip-duplicate.vue'));
Vue.component('admin-trip-create-update', require('./components/trips/admin-trip-create-update.vue'));
Vue.component('cost-manager', require('./components/admin/cost-manager.vue'));
Vue.component('admin-trip-description', require('./components/trips/admin-trip-description.vue'));
Vue.component('deadlines-manager', require('./components/admin/deadlines-manager.vue'));
Vue.component('admin-trip-requirements', require('./components/trips/admin-trip-requirements.vue'));
Vue.component('admin-trip-todos', require('./components/trips/admin-trip-todos.vue'));
Vue.component('admin-interests-list', require('./components/interests/admin-interests-list.vue'));
Vue.component('trip-interest-editor', require('./components/interests/trip-interests-editor.vue'));
Vue.component('promotionals', require('./components/admin/promotionals.vue'));

/**
 * Transportation Components
 */
Vue.component('transports', require('./components/admin/transports.vue'));
Vue.component('transports-details', require('./components/admin/transports-details.vue'));

/**
 * Accommodation Components
 */
Vue.component('rooming-accommodations', require('./components/rooms/rooming-accommodations.vue'));
Vue.component('regions-accommodations', require('./components/regions/regions-accommodations.vue'));

/**
 * Teams Components
 */
Vue.component('team-manager', require('./components/teams/team-manager.vue'));
Vue.component('team-type-manager', require('./components/teams/team-type-manager.vue'));
Vue.component('regions-manager', require('./components/regions/regions-manager.vue'));

/**
 * Rooming Components
 */
Vue.component('rooming-wizard', require('./components/rooms/rooming-wizard.vue'));
Vue.component('rooming-type-manager', require('./components/rooms/rooming-type-manager.vue'));

/**
 * Reservation Components
 */
Vue.component('reservations-list', require('./components/reservations/reservations-list.vue'));
Vue.component('restore-reservation', require('./components/reservations/restore-reservation.vue'));
Vue.component('transfer-reservation', require('./components/reservations/transfer-reservation.vue'));
Vue.component('reservation-avatar', require('./components/reservations/reservation-avatar.vue'));
Vue.component('reservation-costs', require('./components/reservations/reservation-costs.vue'));
Vue.component('reservation-dues', require('./components/reservations/reservation-dues.vue'));
Vue.component('reservation-requirements', require('./components/reservations/reservation-requirements.vue'));
Vue.component('admin-reservations-list', require('./components/reservations/admin-reservations-list.vue'));
Vue.component('admin-reservation-create', require('./components/reservations/admin-reservation-create.vue'));
Vue.component('admin-reservation-edit', require('./components/reservations/admin-reservation-edit.vue'));
Vue.component('admin-reservation-costs', require('./components/reservations/admin-reservation-costs.vue'));
Vue.component('admin-reservation-dues', require('./components/reservations/admin-reservation-dues.vue'));
Vue.component('admin-reservation-deadlines', require('./components/reservations/admin-reservation-deadlines.vue'));
Vue.component('reservations-filters', require('./components/filters/reservations-filters.vue'));
Vue.component('companion-manager', require('./components/reservations/companion-manager.vue'));

/**
 * User Components
 */
Vue.component('user-settings', require('./components/users/user-settings.vue'));
Vue.component('user-profile-countries', require('./components/users/user-profile-countries.vue'));
Vue.component('user-profile-trip-history', require('./components/users/user-profile-trip-history.vue'));
Vue.component('user-profile-stories', require('./components/users/user-profile-stories.vue'));
Vue.component('user-profile-fundraisers', require('./components/users/user-profile-fundraisers.vue'));
Vue.component('user-profile-fundraisers-donors', require('./components/users/user-profile-fundraisers-donors.vue'));
Vue.component('user-profile-fundraisers-progress', require('./components/users/user-profile-fundraisers-progress.vue'));
Vue.component('user-projects-list', require('./components/projects/user-projects-list.vue'));
Vue.component('user-permissions', require('./components/users/user-permissions.vue'));
Vue.component('admin-users-list', require('./components/users/admin-users-list.vue'));
Vue.component('admin-user-create', require('./components/users/admin-user-create.vue'));
Vue.component('admin-user-edit', require('./components/users/admin-user-edit.vue'));
Vue.component('admin-user-delete', require('./components/users/admin-user-delete.vue'));

/**
 * Records Components
 */
Vue.component('records-list', require('./components/records/records-list.vue'));
Vue.component('visas-list', require('./components/records/visas/visas-list.vue'));
Vue.component('medicals-list', require('./components/records/medicals/medicals-list.vue'));
Vue.component('medical-credentials-list', require('./components/records/credentials/medical-credentials-list.vue'));
Vue.component('media-credentials-list', require('./components/records/credentials/media-credentials-list.vue'));
Vue.component('passports-list', require('./components/records/passports/passports-list.vue'));
Vue.component('essays-list', require('./components/records/essays/essays-list.vue'));
Vue.component('influencer-questionnaires-list', require('./components/records/influencers/influencer-questionnaires-list.vue'));
Vue.component('referrals-list', require('./components/records/referrals/referrals-list.vue'));
Vue.component('passport-create-update', require('./components/records/passports/passport-create-update.vue'));
Vue.component('visa-create-update', require('./components/records/visas/visa-create-update.vue'));
Vue.component('medical-create-update', require('./components/records/medicals/medical-create-update.vue'));
Vue.component('medical-credential-create-update', require('./components/records/credentials/medical-credential-create-update.vue'));
Vue.component('media-credential-create-update', require('./components/records/credentials/media-credential-create-update.vue'));
Vue.component('essay-create-update', require('./components/records/essays/essay-create-update.vue'));
Vue.component('influencer-questionnaire-create-update', require('./components/records/influencers/influencer-questionnaire-create-update.vue'));
Vue.component('referral-create-update', require('./components/records/referrals/referral-create-update.vue'));
Vue.component('referral-response', require('./components/referrals/referral-response.vue'));

/**
 * Uploads Components
 */
Vue.component('upload-create-update', require('./components/uploads/admin-upload-create-update.vue'));
Vue.component('admin-uploads-list', require('./components/uploads/admin-uploads-list.vue'));
Vue.component('admin-upload-create-update', require('./components/uploads/admin-upload-create-update.vue'));

/**
 * Projects Components
 */
Vue.component('project-causes', require('./components/admin/project-causes.vue'));
Vue.component('cause-editor', require('./components/admin/cause-editor.vue'));
Vue.component('projects-list', require('./components/admin/projects-list.vue'));
Vue.component('project-editor', require('./components/admin/project-editor.vue'));
Vue.component('project-costs', require('./components/projects/project-costs.vue'));
Vue.component('project-dues', require('./components/projects/project-dues.vue'));
Vue.component('admin-project-costs', require('./components/admin/admin-project-costs.vue'));
Vue.component('admin-project-dues', require('./components/admin/admin-project-dues.vue'));
Vue.component('initiatives-list', require('./components/admin/initiatives-list.vue'));
Vue.component('initiative-editor', require('./components/admin/initiative-editor.vue'));

/**
 * Misc. Components
 */
Vue.component('funding', require('./components/funding.vue'));
Vue.component('notes', require('./components/notes.vue'));
Vue.component('todos', require('./components/todos.vue'));
Vue.component('send-email', require('./components/send-email.vue'));
Vue.component('reports-list', require('./components/reports/reports-list.vue'));

/**
 * Vue Strap Components
 */
Vue.component('modal', require('vue-strap/src/modal'));
Vue.component('accordion', require('vue-strap/src/accordion'));
Vue.component('alert', require('vue-strap/src/alert'));
Vue.component('mm-aside', require('vue-strap/src/aside'));
Vue.component('button-group', require('vue-strap/src/buttonGroup'));
Vue.component('panel', require('vue-strap/src/panel'));
Vue.component('radio', require('vue-strap/src/radio'));
Vue.component('checkbox', require('vue-strap/src/checkbox'));
Vue.component('progressbar', require('vue-strap/src/progressbar'));
Vue.component('strap-select', require('vue-strap/src/select'));
Vue.component('spinner', require('vue-strap/src/spinner'));
Vue.component('popover', require('vue-strap/src/popover'));
Vue.component('tabs', require('vue-strap/src/tabs'));
Vue.component('tab', require('vue-strap/src/tab'));
Vue.component('tooltip', require('vue-strap/src/tooltip'));

/**
 * UI Components
 */
Vue.component('vSelect', require('vue-select'));
Vue.component('dropdown', require('./components/dropdown.vue'));
Vue.component('pagination', require('./components/pagination.vue'));
Vue.component('top-nav', require('./components/top-nav.vue'));
Vue.component('markdown-example-modal', require('./components/markdown-example-modal.vue'));
Vue.component('action-trigger', require('./components/action-trigger.vue'));
Vue.component('action-select', require('./components/action-select.vue'));
Vue.component('listen-text', require('./components/listen-text.vue'));
Vue.component('actionDropdownSelect', require('./components/action-dropdown-select.vue'));
Vue.component('admin-delete-modal', require('./components/admin-delete-modal.vue'));
Vue.component('date-picker', require('./components/date-picker.vue'));
Vue.component('bootstrap-alert-error', {
    props: ['field', 'validator', 'message'],
    template: '<div><div :class="\'alert alert-danger alert-dismissible error-\' + field + \'-\' + validator" role="alert" v-bind="message"></div></div>',
});
Vue.component('phone-input', {
    template: '<div><label for="infoPhone" v-if="label" v-text="label"></label><input ref="input" type="text" id="infoPhone" :class="classes" :value="value" @input="updateValue($event.target.value)" @focus="selectAll" @blur="formatValue" :placeholder="placeholder"></div>',
    props: {
        value: { type: String, default: '' },
        label: { type: String, default: '' },
        classes: { type: String, default: 'form-control'},
        placeholder: { type: String, default: '(123) 456-7890' },

    },
    methods: {
        updateValue(value) {
            if (value === '') {
                this.$refs.input.value = value;
            } else {
                this.$refs.input.value = value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            }
            this.$emit('input', this.$refs.input.value);
        },
        formatValue() {
            if (this.value === '') {
                this.$refs.input.value = this.value;
            } else {
                this.$refs.input.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            }
        },
        selectAll(event) {
            // Workaround for Safari bug
            // http://stackoverflow.com/questions/1269722/selecting-text-on-focus-using-jquery-not-working-in-safari-and-chrome
            setTimeout(function () {
                event.target.select()
            }, 0)
        }
    }
});
