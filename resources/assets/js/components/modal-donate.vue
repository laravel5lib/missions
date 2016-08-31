<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>


        <a class="btn btn-primary btn-block " @click="launchDonate">Donate</a>
        <!--<a class="btn btn-primary btn-justified show-xs" @click="showRight=!showRight">Donate</a>-->
        <hr class="divider inv sm">
        <modal :title="'Donate to ' + recipient" :show.sync="showModal" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <donate :donation-state.sync="donationState" :sub-state.sync="subState" :attempt-submit="attemptSubmit"
                        :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId" :recipient="recipient"></donate>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <!--<button type="button" class="btn btn-default btn-xs" @click="donationState='form',subState=1" v-if="!isState('form', 1)">Reset</button>-->
                <button type="button" class="btn btn-default btn-xs" @click="prevState()" v-if="!isState('form', 1)">Back</button>

                <button type="button" class="btn btn-primary btn-xs" @click="nextState()" v-if="isState('form', 1)">Next</button>
                <button type="button" class="btn btn-primary btn-xs" @click="reviewDonation()" v-if="isState('form', 2)">Review Donation</button>
                <button type="button" class="btn btn-primary btn-xs" @click="createToken" v-if="donationState==='review'">Donate</button>
            </div>
        </modal>

        <aside :show.sync="showRight" placement="right" header="Donate" :width="375">
            <donate :donation-state.sync="donationState" :sub-state.sync="subState" :attempt-submit="attemptSubmit"
                    :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId" :recipient="recipient"></donate>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default btn-xs" @click="donationState='form',subState=1" v-if="!isState('form', 1)">Reset</button>-->
                <button type="button" class="btn btn-default btn-xs" @click="prevState()" v-if="!isState('form', 1)">Back</button>

                <button type="button" class="btn btn-primary btn-xs" @click="nextState()" v-if="isState('form', 1)">Next</button>
                <button type="button" class="btn btn-primary btn-xs" @click="reviewDonation()" v-if="isState('form', 2)">Review Donation</button>
                <button type="button" class="btn btn-primary btn-xs" @click="createToken" v-if="donationState==='review'">Donate</button>
            </div>
        </aside>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import vSelect from 'vue-select';
    import donateComponent from './donate.vue';
    export default{
        name: 'modal-donate',
        components:{ donate: donateComponent, vSelect, 'aside': VueStrap.aside, 'modal': VueStrap.modal },
        props: {
            type: {
                type: String,
                default: null
            },
            typeId: {
                type: String,
                default: null
            },
            recipient: {
                type: String,
                default: 'Missions.Me'
            },
            stripeKey: {
                type: String,
                default: null
            },
            auth: {
                type:String,
                default: '0'
            },
            title: {
                type: String,
                default: ''
            }
        },
        data(){
            return{
                donationState: 'form',
                subState: 1,
                attemptSubmit: false,
                showModal: false,
                showRight: false,
                isMobile: true,
                donateModalOpen: false,
                mediaQuery: null
            }
        },
        watch: {
            'paymentComplete'(val, oldVal) {
                this.$dispatch('payment-complete', val)
            },
        },
        methods: {
            isState(major, minor) {
                return this.donationState === major && this.subState === minor
            },
            nextState(){
                this.$root.$emit('DonateForm:nextState');
            },
            prevState(){
                this.$root.$emit('DonateForm:prevState');
            },
            reviewDonation(){
                this.$root.$emit('DonateForm:reviewDonation');
            },
            createToken(){
                this.$root.$emit('DonateForm:createToken');
            },
            goToState(state){
                switch (state) {
                    case 'form':
                        this.donationState = state;

                        break;
                    case 'review':
                        this.attemptSubmit = true;
                        if (this.$Donation.valid) {
                            this.donationState = state;
                        }
                        break;
                }
            },
            widthChange(mq) {
                if (this.mediaQuery.matches) {
                    // window width is at most 767px
                    this.isMobile = true;
                    if (this.donateModalOpen) {
                        this.showModal = false;
                        this.showRight = true;
                    }
                } else {
                    // window width is greater than 767px
                    this.isMobile = false;
                    if (this.donateModalOpen) {
                        this.showModal = true;
                        this.showRight = false;
                    }
                }

            },
            launchDonate(){
                this.donateModalOpen = true;
                this.widthChange();
            }
        },
        events: {
            'VueStripe::create-card-token': function () {
                return this.createToken();
            },
            'VueStripe::reset-form': function () {
                return this.resetCaching();
            }
        },
        ready: function () {
            // media query event handler
            if (matchMedia) {
                this.mediaQuery = window.matchMedia("(max-width: 767px)");
                this.mediaQuery.addListener(this.widthChange);
                this.widthChange(this.mediaQuery);
            }
        },
    }
</script>
