<template>
    <div>
        <div class="panel-body">
            <div class="row visible-xs-block">
                <div class="col-xs-12">
                    <div class="btn-group btn-group-justified btn-group-xs" style="display:block;" role="group" aria-label="...">
                        <a @click="backStep" class="btn btn-default" :class="{'disabled': currentStep.view === 'step1' }" role="button">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                        <div class="btn-group" role="group">
                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                {{ currentStep.name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
                                    <a @click="toStep(step)">
                                        <span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
                                        {{step.name}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="divider visible-xs">
            <div class="row">
                <div class="col-xs-12 hidden-xs">
                    <ul class="nav nav-pills nav-justified">
                        <li role="step" v-for="step in stepList" :class="{'active': currentStep.view === step.view, 'disabled': currentStep.view !== step.view && !step.complete}">
                            <a @click="toStep(step)">
                                <span class="fa" :class="{'fa-chevron-right':!step.complete, 'fa-check': step.complete}"></span>
                                {{step.name}}
                            </a>
                        </li>
                    </ul>
                    <hr class="divider sm">
                </div>
                <div class="col-xs-12" :class="currentStep.view">
                    <spinner ref="validationSpinner" size="xl" :fixed="false" text="Validating"></spinner>
                    <spinner ref="reservationspinner" size="xl" :fixed="true" text="Creating Reservation"></spinner>
                    <keep-alive>
                        <component :is="currentStep.view" transition="fade" transition-mode="out-in" :for-admin="true" @step-completion="stepCompleted"></component>
                    </keep-alive>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group btn-group" role="group" aria-label="...">
                <!--<a class="btn btn-link" data-dismiss="modal">Cancel</a>-->
                <a class="btn btn-default" @click="backStep()" :class="{'disabled': currentStep.view === 'step1' }">Back</a>
                <a class="btn btn-primary" v-if="!wizardComplete" :class="{'disabled': !canContinue }" @click="nextStep()">Continue</a>
                <a class="btn btn-primary" v-if="wizardComplete" @click="finish()">Finish</a>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import basicInfo from "../trips/registration/basic-info.vue";
    import additionalOptions from '../trips/registration/additional-trip-options.vue';
    import review from '../trips/registration/review.vue';
    export default{
    	name: 'admin-reservation-create',
        props: ['tripId'],
        components: {
            'step1': basicInfo,
            'step2': additionalOptions,
            'step3': review,
        },
        data(){
            return {
                stepList:[
                    {name: 'Basic Info', view: 'step1', complete:false},
                    {name: 'Rooming Options', view: 'step2', complete:false},
                    {name: 'Review', view: 'step3', complete:false}
                ],
                currentStep: null,
//                canContinue: false,
                trip: {},
                tripCosts: {},
                deadlines:[],
                requirements:[],
                wizardComplete: false,

                // user generated data
                userData: null,
                selectedOptions: [],
                userInfo: {},
                paymentInfo: {},
                upfrontTotal: 0,
                fundraisingGoal: 0
            }
        },
        computed: {
            canContinue(){
                return this.currentStep.complete;
            }
        },
        methods: {
            toStep(step){
                if (step.complete) {
                    this.currentStep = step;
                }
            },
            backStep(){
                this.stepList.some(function(step, i, list) {
                    if (this.currentStep.view === step.view){
                        return this.currentStep = list[i-1];
                    }
                }, this);
            },
            nextStep(){
                let thisChild;
                switch (this.currentStep.view) {
                    case 'step1':

                        // find child
                        this.$children.forEach(function (child) {
                            if (child.hasOwnProperty('handle') && child.handle === 'BasicInfo')
                                thisChild = child;
                        });

                        // if form is invalid do not continue
                        thisChild.$validator.validateAll().then(result => {
                            if (!result) {
                                thisChild.attemptedContinue = true;
                                return false;
                            }
                            if (!this.userData) thisChild.setLocalUserData();
                            this.userInfo = thisChild.userInfo;
                            this.nextStepCallback();
                        });

                        break;
                    default:
                        this.nextStepCallback();
                }
            },
            nextStepCallback(){
                this.stepList.some(function(step, i, list) {
                    if (this.currentStep.view === step.view){
                        list[i].complete = this.currentStep.complete;
                        return this.currentStep = list[i+1];
                    }
                }, this);
            },
            finish(){
              let data = {
                // reservation data
                given_names: this.userInfo.firstName,
                surname: this.userInfo.lastName,
                gender: this.userInfo.gender,
                height_a: this.userInfo.heightA,
                height_b: this.userInfo.heightB,
                weight: this.userInfo.weight,
                status: this.userInfo.relationshipStatus,
                shirt_size: this.userInfo.size,
                birthday: moment(this.userInfo.dobMonth + '-' + this.userInfo.dobDay + '-' + this.userInfo.dobYear, 'MM-DD-YYYY').format('YYYY-MM-DD'),
                address: this.userInfo.address,
                city: this.userInfo.city,
                state: this.userInfo.state,
                zip: this.userInfo.zipCode,
                country_code: this.userInfo.country,
                email: this.userInfo.email,
                phone_one: this.userInfo.phone,
                phone_two: this.userInfo.mobile,
                user_id: this.userData.id,
                trip_id: this.tripId,
                companion_limit: this.companion_limit,
                costs: _.union(this.tripCosts.incremental, [this.selectedOptions], this.tripCosts.static),
                desired_role: this.userInfo.desired_role.value
              };
              this.$refs.reservationspinner.show();

                this.$http.post('trips/'+this.tripId+'/register', data).then((response) => {
                    this.$root.$emit('AdminTrip:RefreshReservations');
                    this.$refs.reservationspinner.hide();
                    location.href = '/admin/trips/'+this.tripId+'/reservations';
                }, (response) =>  {
                    console.log(response);
                    this.$refs.reservationspinner.hide();
                });
            },
            stepCompleted(val) {
                this.currentStep.complete = val;
                if (this.currentStep.view === 'step3')
                    this.wizardComplete = val;
            },
        },
        created(){
            // login component skipped for now
            this.currentStep = this.stepList[0];
        },
        events: {
            'basic-info'(val){
                this.currentStep.complete = val
            },
            'ato-complete'(val){
                this.currentStep.complete = val;
            },
            'review'(val){
                this.currentStep.complete = this.wizardComplete = val;
            }
        },
        mounted(){
            //get trip costs
            this.$http.get('trips/' + this.tripId, { params: { include: 'priceables' }}).then((response) => {
              let optionalArr = [], staticArr = [], incrementalArr = [];

              this.trip = response.data.data;
                // deadlines, requirements, and companion_limit
                // this.deadlines =  response.data.data.deadlines.data;
                // this.requirements =  response.data.data.requirements.data;
                this.companion_limit = response.data.data.companion_limit;

                // filter costs by type
                response.data.data.prices.forEach(function (price) {
                    switch (price.cost.type) {
                        case 'Additional':
                            staticArr.push(price);
                            break;
                        case 'Registration':
                            incrementalArr.push(price);
                            break;
                        case 'Rooming':
                            optionalArr.push(price);
                            break;
                    }
                });
                this.tripCosts = {
                    static: staticArr,
                    incremental: incrementalArr,
                    optional: optionalArr,
                }
            });
        }
    }
</script>