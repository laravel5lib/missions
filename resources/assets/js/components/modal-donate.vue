<template >
    <div>
        <div class="text-center">
        <a class="btn btn-success" data-toggle="collapse" href="#collapseDonate" aria-expanded="false" aria-controls="collapseDonate">Donate<span class="hidden-sm"> To The Cause</span></a>
        </div>
        <hr class="divider inv sm">
        <div class="panel panel-default collapse" id="collapseDonate">
            <div class="panel-body">
                <donate :donation-state="donationState" :sub-state="subState" :attempt-submit="attemptSubmit" :title="title"
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
                <a href="https://stripe.com/" target="_blank"><span style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span> <img style="width:90px; height:20px;opacity:.65;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAO4AAAA0CAMAAAC6lfRZAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABhlBMVEVCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3AAAAAiF6mbAAAAgXRSTlMACipBT1dZVUtADDpYG1Q7HwsCBA4nPQ08HikJOR04JEplfYBQWgETLklkBy93CH9qNBhEFBwrJRcFEhUDJigaVnxiLUdsTlt2e0xvfnFrdHpyc2kWeBEhIE1tcHUyLF0ZIyIGEDMxMA9geVw/SDU2UjdmU2hCZ1FfXm4+RWNGYUOYjmKOAAAAAWJLR0SBErqu/gAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB+AJHg04Eou4rR8AAAcFSURBVGje7Zr5Qxo5FMefWIZyKJdcIlh5SK3XKN53FS+KCFq12taujrYVa+259nC37Z++eZnhFHSwVjz2+wMJYZJ5n0zy8jIEQFaVpvqOVriR0t3VawyQqyqjqdJG/VmZjFVZ2ppaQWu2WG1wI2Wvs5i1gs6S/u4wCU5XpY36s3K5BcEhZz2Cqb7S5vx51ZsED6VVOuEW0DJeQUfz1yi4K23J5cgtGAGsJm3V7zd1HeTVmqygERoqbUf5svv856jVIGhAL/gqbXyZarzXFEA8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" alt="Powered by Stripe"></a>
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
        mounted() {
            // media query event handler
            /*if (matchMedia) {
                this.mediaQuery = window.matchMedia("(max-width: 767px)");
                this.mediaQuery.addListener(this.widthChange);
                this.widthChange(this.mediaQuery);
            }*/
        },
    }
</script>
