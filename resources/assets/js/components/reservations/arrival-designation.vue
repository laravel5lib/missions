<template>
    <div>
        <div v-if="!editMode">
            <label>{{ reservation.arrival_designation | capitalize }} Missionary</label>
        </div>
        <div class="row" v-else>
            <div class="col-xs-8">
                <label>Select designation</label>
                <div class="form-inline">
                    <select v-model="arrival_designation" @change="updateInfo" class="form-control">
                        <option value="" selected>Select</option>
                        <option value="eastern">Eastern Missionary</option>
                        <option value="western">Western Missionary</option>
                        <option value="international">International</option>
                    </select>
                    <button class="btn btn-primary btn-md" @click="save">Save</button>
                </div>
            </div>
            <div class="col-xs-4">
            </div>
        </div>
        <hr class="divider inv">
        <p class="small" v-text="moreInfo"></p>
        <p v-if="!editMode"><button @click="editMode = !editMode" class="btn btn-default-hollow btn-xs">Edit</button></p>
    </div>
</template>
<script>
    export default {
        name: 'arrival-designation',
        props: {
            'reservationId': {
                type: String,
                required: true
            },
            'requirementId': {
                type: String,
                required: true
            }
        },
        data() {
            return {
                reservation: {},
                arrival_designation: '',
                editMode: false,
                moreInfo: '',
                eastern: 'Missionaries traveling from the Eastern, Central or Mountain US timezones. Miami registration at the Miami Airport Marriott Campus will open to you at 8am on July 22. You will not be able to check into your hotel room until after training at the Fillmore is complete. Shuttles to the Fillmore begin at 9:30am and continue through 1pm. There will be a secured luggage drop where you can keep your luggage during the training event. Estimated hotel check-in will be available at 10pm on July 22. Check out is before 12pm noon on July 23. Eastern missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 22.',
                western: 'Missionaries traveling from the Pacific, Alaska, or Hawaiian timezones. Miami Registration at the Miami Airport Marriott campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and the start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre There will be a secured luggage drop where you can keep your luggage during your time at South Beach. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. Western Missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.',
                international: 'Missionaries traveling from a nation outside of the United States with the exception of the eastern territories of Canada. Miami Registration at the Miami Airport Marriott Campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21 but as International Missionaries, you will receive first preference on rooms available for early check-in. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre. There will be a secured luggage drop where you can keep your luggage during the Training Event. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. International missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.'
            }
        },
        methods: {
            updateInfo() {
                if(this.arrival_designation == 'eastern') {
                    this.moreInfo = this.eastern;
                } else if(this.arrival_designation == 'western') {
                    this.moreInfo = this.western;
                } else if(this.arrival_designation == 'international') {
                    this.moreInfo = this.international;
                } else {
                    this.moreInfo = '';
                }
            },
            fetch() {
                this.$http.get('reservations/' + this.reservationId).then(function (response) {
                    this.reservation = response.data.data;
                    this.arrival_designation = this.reservation.arrival_designation;
                    if(!this.arrival_designation) this.editMode = true;
                    this.updateInfo();
                });
            },
            save() {
                this.$http.put('reservations/' + this.reservationId, {arrival_designation: this.arrival_designation}).then(function (response) {
                    this.reservation = response.data.data;
                    this.editMode = false;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>