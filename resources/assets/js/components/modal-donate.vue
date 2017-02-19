<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <div class="text-center">
        <a class="btn btn-success" data-toggle="collapse" href="#collapseDonate" aria-expanded="false" aria-controls="collapseDonate">Donate<span class="hidden-sm"> To The Cause</span></a>
        </div>
        <hr class="divider inv sm">
        <div class="panel panel-default collapse" id="collapseDonate">
            <div class="panel-body">
                <donate :donation-state.sync="donationState" :sub-state.sync="subState" :attempt-submit="attemptSubmit" :title="title"
                        :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId" :fund-id="fundId" :recipient="recipient" identifier="modal"></donate>
                <!--<button type="button" class="btn btn-default btn-xs" @click="donationState='form',subState=1" v-if="!isState('form', 1)">Reset</button>-->
                <div class="text-center">
                    <button type="button" class="btn btn-default btn-sm" @click="prevState()" v-if="!isState('form', 1)"><i style="margin-right:3px;font-size:.8em;" class="fa fa-chevron-left"></i> Back</button>

                    <button type="button" class="btn btn-primary btn-sm" @click="nextState()" v-if="isState('form', 1)">Next <i style="margin-left:3px;font-size:.8em;" class="fa fa-chevron-right"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" @click="reviewDonation('modal')" v-if="isState('form', 2)">Review</button>
                    <button type="button" class="btn btn-primary btn-sm" @click="donate('modal')" v-if="donationState==='review'">Donate</button>
                    <button type="button" class="btn btn-success btn-sm" @click="done" v-if="donationState==='confirmation'">Close</button>
                </div><!-- end buttons -->
            </div><!-- end panel-body -->
            <div class="panel-footer text-center">
                <a href="https://stripe.com/" target="_blank"><span style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span> <img style="width:90px; height:20px;opacity:.65;" src="../images/powered-by-stripe@2x.png" alt="Powered by Stripe"></a>
            </div>
        </div><!-- end panel -->
    </div>
</template>
<script type="text/javascript">
    import vSelect from 'vue-select';
    import donateComponent from './donate.vue';
    export default{
        name: 'modal-donate',
        components:{ donate: donateComponent, vSelect },
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
                $('#collapseDonate').collapse('hide');
                let t = setTimeout(this.resetState, 1000);
            }
        },
        ready: function () {
            // media query event handler
            /*if (matchMedia) {
                this.mediaQuery = window.matchMedia("(max-width: 767px)");
                this.mediaQuery.addListener(this.widthChange);
                this.widthChange(this.mediaQuery);
            }*/
        },
    }
</script>
