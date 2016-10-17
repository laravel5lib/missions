<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <div class="text-center">
        <a class="btn btn-success" @click="launchDonate">Donate<span class="hidden-sm"> To The Cause</span></a>
        <!--<a class="btn btn-primary btn-justified show-xs" @click="showRight=!showRight">Donate</a>-->
        </div>
        <hr class="divider inv sm">
        <modal :title="'Donate to ' + title" :show.sync="showModal" effect="fade" width="500">
            <div slot="modal-body" class="modal-body">
                <donate :donation-state.sync="donationState" :sub-state.sync="subState" :attempt-submit="attemptSubmit" :title="title"
                        :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId" :fund-id="fundId" :recipient="recipient" identifier="modal"></donate>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <!--<button type="button" class="btn btn-default btn-xs" @click="donationState='form',subState=1" v-if="!isState('form', 1)">Reset</button>-->
                <button type="button" class="btn btn-default" @click="prevState()" v-if="!isState('form', 1)">Back</button>

                <button type="button" class="btn btn-primary" @click="nextState()" v-if="isState('form', 1)">Next</button>
                <button type="button" class="btn btn-primary" @click="reviewDonation('modal')" v-if="isState('form', 2)">Review</button>
                <button type="button" class="btn btn-primary" @click="donate('modal')" v-if="donationState==='review'">Donate</button>
                <button type="button" class="btn btn-success" @click="done" v-if="donationState==='confirmation'">Close</button>
            </div>
        </modal>

        <aside :show.sync="showRight" placement="right" header="Donate" :width="375">
            <div class="col-sm-12">
                <donate :donation-state.sync="donationState" :sub-state.sync="subState" :attempt-submit="attemptSubmit" :title="title"
                        :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId" :fund-id="fundId" :recipient="recipient" identifier="aside"></donate>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn-default btn-xs" @click="donationState='form',subState=1" v-if="!isState('form', 1)">Reset</button>-->
                    <button type="button" class="btn btn-default btn-xs" @click="prevState()" v-if="!isState('form', 1) && donationState!=='confirmation'">Back</button>
                    <button type="button" class="btn btn-primary btn-xs" @click="nextState()" v-if="isState('form', 1)">Next</button>
                    <button type="button" class="btn btn-primary btn-xs" @click="reviewDonation('aside')" v-if="isState('form', 2)">Review Donation</button>
                    <button type="button" class="btn btn-primary btn-xs" @click="donate('aside')" v-if="donationState==='review'">Donate</button>
                    <button type="button" class="btn btn-success btn-xs" @click="done" v-if="donationState==='confirmation'">Close</button>
                </div>
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
            fundId: {
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
            resetState(){
                this.$root.$emit('DonateForm:resetState');
            },
            reviewDonation(identifier){
                this.$root.$emit('DonateForm:reviewDonation', identifier);
            },
            donate(identifier){
                this.$root.$emit('DonateForm:donate', identifier);
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
            },
            done(){
                this.donateModalOpen = false;
                this.showModal = false;
                this.showRight = false;
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
