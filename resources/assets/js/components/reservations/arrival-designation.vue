<template>
    <div>
        <div class="row">
            <div class="col-xs-12" v-if="! editMode">
                <h6 class="text-uppercase">
                    <i class="fa fa-map-marker"></i> {{ designation.content }} Arrival
                    <button class="btn btn-xs btn-default-hollow pull-right" @click="editMode = true"><i class="fa fa-pencil"></i> Change</button>
                </h6>
            </div>
            <div class="col-xs-12" v-else>
                <h6 class="text-uppercase"><i class="fa fa-map-marker"></i>Select designation</h6>
                <select v-model="designation.content" @change="updateInfo" class="form-control">
                    <option value="">Select</option>
                    <option value="eastern">Eastern Missionary</option>
                    <option value="western">Western Missionary</option>
                    <option value="international">International</option>
                </select>
            </div>
        </div>
        <hr class="divider lg">
        <p class="small" v-text="moreInfo"></p>
        <div class="row" v-if="editMode">
            <div class="col-sm-12 text-center">
                <hr class="divider inv">
                <button class="btn btn-default" @click="cancel()">Cancel</button>
                <button class="btn btn-primary" @click="save()">Update</button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'arrival-designation',
        props: {
            'document': {
                type: Object,
                required: false
            },
            'reservationId': {
                type: String,
                required: true
            }
        },
        computed: {
            designation: function() {
                if(this.document) {
                    return this.document
                } else {
                    return { content: ["eastern"] };
                }
            }
        },
        data() {
            return {
                editMode: true,
                moreInfo: '',
                eastern: 'Missionaries traveling from the Eastern, Central or Mountain US timezones. Miami registration at the Miami Airport Marriott Campus will open to you at 8am on July 22. You will not be able to check into your hotel room until after training at the Fillmore is complete. Shuttles to the Fillmore begin at 9:30am and continue through 1pm. There will be a secured luggage drop where you can keep your luggage during the training event. Estimated hotel check-in will be available at 10pm on July 22. Check out is before 12pm noon on July 23. Eastern missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 22.',
                western: 'Missionaries traveling from the Pacific, Alaska, or Hawaiian timezones. Miami Registration at the Miami Airport Marriott campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and the start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre There will be a secured luggage drop where you can keep your luggage during your time at South Beach. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. Western Missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.',
                international: 'Missionaries traveling from a nation outside of the United States with the exception of the eastern territories of Canada. Miami Registration at the Miami Airport Marriott Campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21 but as International Missionaries, you will receive first preference on rooms available for early check-in. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre. There will be a secured luggage drop where you can keep your luggage during the Training Event. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. International missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.'
            }
        },
        methods: {
            updateInfo() {
                if(this.designation.content == 'eastern') {
                    this.moreInfo = this.eastern;
                } else if(this.designation.content == 'western') {
                    this.moreInfo = this.western;
                } else if(this.designation.content == 'international') {
                    this.moreInfo = this.international;
                } else {
                    this.moreInfo = '';
                }
            },
            save() {
                if(this.document) {
                    this.$http.delete('questionnaires/' + this.document.id).then(function (response) {
                        console.log('old removed');
                    });
                }
                this.$http.post('questionnaires/', {
                    content: [this.designation.content],
                    reservation_id: this.reservationId,
                    type: 'arrival_designation'
                }).then(function (response) {
                    this.designation = response.data.data;
                    this.editMode = false;
                    this.setDesignation(response.data.data);
                });
            },
            setDesignation(designation) {
              this.$dispatch('set-document', designation);
            },
            cancel() {
                this.editMode = false;
                if(this.document) {
                    this.designation = this.document
                }
            }
        },
        ready() {
            if(this.document) {
                this.editMode = false;
            }

            this.updateInfo();
        }
    }
</script>