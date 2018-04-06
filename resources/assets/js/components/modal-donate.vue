<template>
	<div>
		<slot name="button" :trigger="launchDonate">
			<button type="button" class="btn btn-success btn-block btn-lg" @click="launchDonate">Donate Now</button>
		</slot>
		<modal :value="donateModalOpen" @closed="donateModalOpen=false" effect="zoom">
			<div slot="modal-body" class="modal-body">
				<div class="panel-body">
					<donate ref="donator" :attempt-submit="attemptSubmit" @state-changed="stateChanged"
					        :child="true" :stripe-key="stripeKey" :auth="auth" :type="type" type-id="typeId"
					        :fund-id="fundId" :recipient="recipient" identifier="modal"></donate>
					<div class="text-center">
						<template v-if="isState('form', 1)">
							<div class="row">
								<div class="col-sm-offset-2 col-sm-8 col-xs-12">
									<button type="button" class="btn btn-success btn-block" @click="nextState()">
										<i class="fa fa-credit-card icon-left"></i> Pay By Card
									</button>
									<hr class="divider sm inv">
									<a role="button" onclick="$('.collapse').collapse('toggle')">
										<i class="fa fa-chevron-down icon-left"></i> Other ways to give
									</a>
									<hr class="divider sm inv">
									<div class="collapse text-left" id="otherWaysGive">
										<h5>Mail your donation!</h5>
										<p class="small">Make checks payable to <strong>Missions.Me</strong>. Drop your check in the mail to:
										</p>
										<address style="font-style:italic;">
											145 S. Livernois Rd. <br>
											Suite 308 <br>
											Rochester Hills, MI 48307 <br>
										</address>
										<p class="small">
											Please allow up to 14 business days for check donations to be processed.</p>
									</div>
								</div><!-- end col -->
							</div><!-- end row -->
						</template>

						<button type="button" class="btn btn-primary btn-md" @click="reviewDonation('modal')"
						        v-if="isState('form', 2)">Review Donation
						</button>
						<button type="button" class="btn btn-primary btn-md" @click="donate('modal')"
						        v-if="donationState==='review'">Donate
						</button>
						<button type="button" class="btn btn-success-hollow btn-md" @click="done"
						        v-if="donationState==='confirmation'">Finished
						</button>
						<hr class="divider sm inv">
						<a class="text-muted" @click="prevState()" v-if="!isState('form', 1) && donationState!=='confirmation' ">
							<p><i class="fa fa-chevron-left icon-left"></i> Back</p>
						</a>
					</div><!-- end buttons -->
				</div><!-- end panel-body -->
				<hr class="divider sm inv">
				<ol class="carousel-indicators" style="bottom:0;" v-if="donationState !== 'confirmation'">
					<li :class="{'active': isState('form', 1)}"></li>
					<li :class="{'active': isState('form', 2)}"></li>
					<li :class="{'active': isState('review', 2)}"></li>
				</ol>
			</div>
			<div slot="modal-footer">
				<div class="panel-footer text-center">
					<a href="https://stripe.com/" target="_blank"><span
							style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span>
						<img style="width:90px; height:20px;opacity:.65;"
						     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAO4AAAA0CAMAAAC6lfRZAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABhlBMVEVCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3BCR3AAAAAiF6mbAAAAgXRSTlMACipBT1dZVUtADDpYG1Q7HwsCBA4nPQ08HikJOR04JEplfYBQWgETLklkBy93CH9qNBhEFBwrJRcFEhUDJigaVnxiLUdsTlt2e0xvfnFrdHpyc2kWeBEhIE1tcHUyLF0ZIyIGEDMxMA9geVw/SDU2UjdmU2hCZ1FfXm4+RWNGYUOYjmKOAAAAAWJLR0SBErqu/gAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB+AJHg04Eou4rR8AAAcFSURBVGje7Zr5Qxo5FMefWIZyKJdcIlh5SK3XKN53FS+KCFq12taujrYVa+259nC37Z++eZnhFHSwVjz2+wMJYZJ5n0zy8jIEQFaVpvqOVriR0t3VawyQqyqjqdJG/VmZjFVZ2ppaQWu2WG1wI2Wvs5i1gs6S/u4wCU5XpY36s3K5BcEhZz2Cqb7S5vx51ZsED6VVOuEW0DJeQUfz1yi4K23J5cgtGAGsJm3V7zd1HeTVmqygERoqbUf5svv856jVIGhAL/gqbXyZarzXFEA8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"
						     alt="Powered by Stripe"></a>
				</div>

			</div>
		</modal>
	</div>
</template>
<style>
	.carousel-indicators li {
		background-color: #999999;
	}

	.carousel-indicators .active {
		background-color: #F33644;
	}
</style>
<script type="text/javascript">
  import vSelect from 'vue-select';
  import donateComponent from './donate.vue';

  export default {
    name: 'modal-donate',
    components: {donate: donateComponent, vSelect},
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
        type: String,
        default: '0'
      },
      title: {
        type: String,
        default: ''
      }
    },
    data() {
      return {
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
      paymentComplete(val, oldVal) {
        this.$emit('payment-complete', val)
      },
    },
    methods: {
      isState(major, minor) {
        return this.donationState === major && this.subState === minor
      },
      nextState() {
        this.$root.$emit('DonateForm:nextState');
      },
      prevState() {
        this.$root.$emit('DonateForm:prevState');
      },
      resetState() {
        this.$root.$emit('DonateForm:resetState');
      },
      reviewDonation(identifier) {
        this.$root.$emit('DonateForm:reviewDonation', identifier);
      },
      donate(identifier) {
        this.$root.$emit('DonateForm:donate', identifier);
      },
      stateChanged(donationState, subState) {
        this.donationState = donationState;
        this.subState = subState;
      },
      goToState(state) {
        switch (state) {
          case 'form':
            this.donationState = state;

            break;
          case 'review':
            this.attemptSubmit = true;
            this.$refs.donator.$validator.validateAll('form-2').then(result => {
              if (result)
                this.donationState = state;
            });
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
      launchDonate() {
        this.donateModalOpen = true;
        this.widthChange();
      },
      done() {
        let t;
        this.donateModalOpen = false;
        this.showModal = false;
        this.showRight = false;
        $('#collapseDonate').collapse('hide');
        t = setTimeout(this.resetState, 1000);
      }
    },
    mounted() {
      this.$root.$on('modal-donate:open', () => {
        this.launchDonate();
      });
      // media query event handler
      if (matchMedia) {
        this.mediaQuery = window.matchMedia("(max-width: 767px)");
        this.mediaQuery.addListener(this.widthChange);
        this.widthChange(this.mediaQuery);
      }

      this.$nextTick(function () {
        // $('.collapse').collapse()
      });
    },
  }
</script>
