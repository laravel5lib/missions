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
Vue.component('collapse-donate', require('./components/collapse-donate.vue'));
Vue.component('modal-donate', require('./components/modal-donate.vue'));
Vue.component('donations-list', require('./components/reservations/donations-list.vue'));
Vue.component('reconcile-fund', require('./components/reconcile-fund.vue'));
Vue.component('admin-donors-list', require('./components/financials/donors/admin-donors-list.vue'));
Vue.component('admin-funds-list', require('./components/financials/funds/admin-funds-list.vue'));
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
Vue.component('fundraiser-updates', require('./components/fundraisers/FundraiserUpdates.vue'));
Vue.component('fundraiser-collection', require('./components/fundraisers/fundraiser-collection.vue'));
Vue.component('fundraiser-sharing', require('./components/fundraisers/FundraiserSharing.vue'));
Vue.component('fundraiser-countdown', require('./components/fundraisers/FundraiserCountdown.vue'));
Vue.component('fundraiser-create', require('./components/fundraisers/FundraiserCreate.vue'));
Vue.component('fundraisers-description', require('./components/fundraisers/fundraisers-description.vue'));
Vue.component('anchored-heading', {
  render(createElement) {
    return createElement(
      'h' + this.level,   // tag name
      this.$slots.default // array of children
    )
  },
  props: {
    level: {
      type: Number,
      required: true
    }
  }
});

/**
 * Group components
 */
Vue.component('groups', require('./components/groups/groups.vue'));
Vue.component('group-profile-stories', require('./components/groups/group-profile-stories.vue'));
Vue.component('group-interest-signup', require('./components/groups/group-interest-signup.vue'));
Vue.component('admin-group-create', require('./components/groups/admin-group-create.vue'));
Vue.component('admin-group-edit', require('./components/groups/admin-group-edit.vue'));
Vue.component('admin-group-managers', require('./components/groups/admin-group-managers.vue'));

/**
 * Campaign and Trip Components
 */
Vue.component('trip-registration-wizard', require('./components/trips/trip-registration-wizard.vue'));
Vue.component('admin-campaign-trips', require('./components/campaigns/admin-campaign-trips.vue'));
Vue.component('admin-trip-todos', require('./components/trips/admin-trip-todos.vue'));
Vue.component('promotionals', require('./components/admin/promotionals.vue'));
// @todo remove with facilitators view
Vue.component('admin-trip-facilitators', require('./components/trips/admin-trip-facilitators.vue'));
// @todo remove with projects
Vue.component('cost-manager', require('./components/admin/cost-manager.vue'));
// @todo remove with deadlines manager tab in trips and projects
Vue.component('deadlines-manager', require('./components/admin/deadlines-manager.vue'));

/**
 * @todo  REMOVE ALL TRANSPORTS LOGIC
 * Transportation Components
 */
Vue.component('transports', require('./components/admin/transports.vue'));
Vue.component('transports-details', require('./components/admin/transports-details.vue'));

/**
 * @todo REMOVE ALL ACCOMMODATION CODE
 * Accommodation Components
 */
Vue.component('rooming-accommodations', require('./components/rooms/rooming-accommodations.vue'));
Vue.component('regions-accommodations', require('./components/regions/regions-accommodations.vue'));

/**
 * @todo  REMOVE ALL TEAMS CODE
 * Teams Components
 */
Vue.component('team-manager', require('./components/teams/team-manager.vue'));
Vue.component('team-type-manager', require('./components/teams/team-type-manager.vue'));
Vue.component('regions-manager', require('./components/regions/regions-manager.vue'));

/**
 * @todo REMOVE ALL ROOMING CODE
 * Rooming Components
 */
Vue.component('rooming-wizard', require('./components/rooms/rooming-wizard.vue'));
Vue.component('rooming-type-manager', require('./components/rooms/rooming-type-manager.vue'));

/**
 * Reservation Components
 */
Vue.component('restore-reservation', require('./components/reservations/restore-reservation.vue'));
Vue.component('transfer-reservation', require('./components/reservations/transfer-reservation.vue'));
Vue.component('companion-manager', require('./components/reservations/companion-manager.vue'));
Vue.component('admin-reservation-create', require('./components/reservations/admin-reservation-create.vue'));
Vue.component('admin-reservation-edit', require('./components/reservations/admin-reservation-edit.vue'));
// @todo delete with rooming, transports, etc.
Vue.component('reservations-filters', require('./components/filters/reservations-filters.vue'));

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
 * Representative Components
 */
Vue.component('representative-list', require('./components/representatives/RepresentativeList.vue'));
Vue.component('representative-edit-form', require('./components/representatives/RepresentativeEditForm.vue'));

/**
 * Records Components
 */
Vue.component('passport-create-update', require('./components/records/passports/passport-create-update.vue'));
Vue.component('visa-create-update', require('./components/records/visas/visa-create-update.vue'));
Vue.component('medical-create-update', require('./components/records/medicals/medical-create-update.vue'));
Vue.component('medical-credential-create-update', require('./components/records/credentials/medical-credential-create-update.vue'));
Vue.component('media-credential-create-update', require('./components/records/credentials/media-credential-create-update.vue'));
Vue.component('essay-create-update', require('./components/records/essays/essay-create-update.vue'));
Vue.component('influencer-questionnaire-create-update', require('./components/records/influencers/influencer-questionnaire-create-update.vue'));
Vue.component('referral-create-update', require('./components/records/referrals/referral-create-update.vue'));
Vue.component('referral-response', require('./components/referrals/referral-response.vue'));
Vue.component('airport-preference', require('./components/reservations/airport-preference.vue'));
Vue.component('arrival-designation', require('./components/reservations/arrival-designation.vue'));
Vue.component('minor-release', require('./components/reservations/minor-release.vue'));

/**
 * Uploads Components
 */
Vue.component('upload-create-update', require('./components/uploads/admin-upload-create-update.vue'));
Vue.component('admin-uploads-list', require('./components/uploads/admin-uploads-list.vue'));
Vue.component('admin-upload-create-update', require('./components/uploads/admin-upload-create-update.vue'));

/**
 * @todo DELETE WITH PROJECTS CODE
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
 * Wrapper Components
 */
Vue.component('price-list', require('./components/price-list.vue'));
Vue.component('price-add-new', require('./components/price-add-new.vue'));
Vue.component('price-edit', require('./components/price-edit.vue'));
Vue.component('price-delete', require('./components/price-delete.vue'));
Vue.component('price-push', require('./components/price-push.vue'));
Vue.component('meta-form', require('./components/MetaForm.vue'));
Vue.component('group-manager', require('./components/GroupManager.vue'));
Vue.component('campaign-cost-manager', require('./components/CampaignCostManager.vue'));
Vue.component('group-reservation-list', require('./components/group-reservation-list.vue'));
Vue.component('reservation-pricing-overview', require('./components/ReservationPricingOverview.vue'));
Vue.component('flight-list', require('./components/FlightList.vue'));
Vue.component('missionary-list', require('./components/MissionaryList.vue'));
Vue.component('dropped-reservation-list', require('./components/DroppedReservationList.vue'));
Vue.component('itinerary-flight-manager', require('./components/ItineraryFlightManager.vue'));
Vue.component('group-flight-list', require('./components/GroupFlightList.vue'));
Vue.component('interest-list', require('./components/InterestList.vue'));
Vue.component('group-interest-list', require('./components/GroupInterestList.vue'));
Vue.component('squad-manager', require('./components/SquadManager.vue'));
Vue.component('group-squad-member-list', require('./components/GroupSquadMemberList.vue'));
Vue.component('group-edit', require('./components/GroupEdit.vue'));
Vue.component('travel-requirement-form', require('./components/TravelRequirementForm.vue'));
Vue.component('requirements-manager', require('./components/RequirementsManager.vue'));
Vue.component('travel-document', require('./components/TravelDocument.vue'));
Vue.component('travel-document-detail', require('./components/TravelDocumentDetail.vue'));
Vue.component('travel-documents-list', require('./components/TravelDocumentsList.vue'));
Vue.component('bulk-add-requirement-modal', require('./components/BulkAddRequirementModal.vue'));
Vue.component('tag-list', require('./components/TagList.vue'));
Vue.component('admin-transactions-list', require('./components/AdminTransactionList.vue'));

/**
 * Utility Components
 */
Vue.component('delete-form', require('./components/DeleteForm.vue'));
Vue.component('fetch-json', require('./components/fetch-json.vue'));
Vue.component('list-filters', require('./components/ListFilters.vue'));
Vue.component('pager', require('./components/pager.vue'));
Vue.component('data-list', require('./components/data-list.vue'));
Vue.component('ajax-form', require('./components/ajax-form.vue'));
Vue.component('input-text', require('./components/input-text.vue'));
Vue.component('input-textarea', require('./components/input-textarea.vue'));
Vue.component('input-date', require('./components/input-date.vue'));
Vue.component('input-time', require('./components/input-time.vue'));
Vue.component('input-datetime', require('./components/input-datetime.vue'));
Vue.component('input-currency', require('./components/input-currency.vue'));
Vue.component('input-phone', require('./components/input-phone.vue'));
Vue.component('input-number', require('./components/input-number.vue'));
Vue.component('input-select', require('./components/input-select.vue'));
Vue.component('input-markdown', require('./components/input-markdown.vue'));
Vue.component('input-checkbox', require('./components/input-checkbox.vue'));
Vue.component('input-radio-group', require('./components/input-radio-group.vue'));
Vue.component('select-country', require('./components/select-country.vue'));
Vue.component('select-prospects', require('./components/select-prospects.vue'));
Vue.component('select-roles', require('./components/select-roles.vue'));
Vue.component('select-group', require('./components/select-group.vue'));
Vue.component('select-rep', require('./components/select-rep.vue'));
Vue.component('select-cost', require('./components/select-cost.vue'));
Vue.component('select-price', require('./components/select-price.vue'));
Vue.component('select-user', require('./components/select-user.vue'));
Vue.component('select-timezone', require('./components/select-timezone.vue'));
Vue.component('select-tags', require('./components/select-tags.vue'));
Vue.component('alert-error', require('./components/alert-error.vue'));
Vue.component('alert-success', require('./components/alert-success.vue'));
Vue.component('datetime-formatted', require('./components/datetime-formatted.vue'));
Vue.component('export-list', require('./components/ExportList.vue'));

/**
 * Vue Strap Components
 */
let VueStrap = require('vue-strap');
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('mm-aside', VueStrap.aside);
Vue.component('button-group', VueStrap.buttonGroup);
Vue.component('panel', VueStrap.panel);
Vue.component('radio', VueStrap.radio);
Vue.component('checkbox', VueStrap.checkbox);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('strap-select', VueStrap.select);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabs);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);

/**
 * UI Components
 */
Vue.component('video-block', require('./components/fundraisers/content-components/video.vue'));
Vue.component('vSelect', require('vue-select'));
// Vue.component('dropdown', VueStrap.dropdown);
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
Vue.component('currency-input', {
  template: '<div><label :for="elementId" v-if="label" v-text="label"></label><div class="input-group"><span class="input-group-addon" v-text="symbol"></span><input ref="input" type="text" :id="elementId" class="form-control" :class="classes" :value="value" @input="updateValue($event.target.value)" @focus="selectAll" @blur="formatValue" :placeholder="placeholder"><span class="input-group-addon">USD</span></div></div>',
  props: {
    value: {type: String, default: ''},
    label: {type: String, default: ''},
    symbol: {type: String, default: '$'},
    //currency: {type: String, default: 'USD'},
    classes: {type: String, default: ''},
    placeholder: {type: String, default: ''},
  },
  data() { return {elementId: _.uniqueId('currency-input-')} },
  methods: {
    /*updateValue(value) {
      let num;
      if (value) {
        if (value === '') {
          this.$refs.input.value = value;
        } else {
          num = Number(value.replace(/\D/g, ''));
          this.$refs.input.value = isNaN(num) ? '' : num.toLocaleString();
        }
      }
      this.$emit('input', this.$refs.input.value);
    },*/
    updateValue(value) {
      if (value === '' || value === null) {
        this.$refs.input.value = value;
      } else {
        this.$refs.input.value = value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
      }
      this.$emit('input', this.$refs.input.value);
    },
    formatValue() {
      if (this.value === '' || this.value === null) {
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
Vue.component('phone-input', {
  template: '<div><label for="infoPhone" v-if="label" v-text="label"></label><input ref="input" :name="name" type="text" id="infoPhone" :class="classes" :value="value" @input="updateValue($event.target.value)" @focus="selectAll" @blur="formatValue" :placeholder="placeholder"></div>',
  props: {
    value: {type: String, default: ''},
    label: {type: String, default: ''},
    classes: {type: String, default: 'form-control'},
    placeholder: {type: String, default: '(123) 456-7890'},
    name: {type: String, default: 'phone'}
  },
  methods: {
    updateValue(value) {
      if (value === '' || value === null) {
        this.$refs.input.value = value;
      } else {
        this.$refs.input.value = value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
      }
      this.$emit('input', this.$refs.input.value);
    },
    formatValue() {
      if (this.value === '' || this.value === null) {
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
  },
  mounted() {
      if (this.value)
        this.formatValue();
  }
});