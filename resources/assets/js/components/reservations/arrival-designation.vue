<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>Arrival Designation</h5>
                </div>
                <div class="col-xs-4 text-right" v-if="! editMode">
                    <button class="btn btn-sm btn-link" @click="editMode = true" v-if="!locked">
                        Edit
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <template v-if="editMode">
                <div class="alert alert-warning"><i class="fa fa-warning icon-left"></i> Please verify your arrival designation with your team coordinator and travel companions <strong>before</strong> submitting a selection.</div>

                <label>Select designation</label>
                <select v-model="designation.content" @change="updateInfo" class="form-control">
                    <option value="">Select</option>
                    <option value="eastern">Eastern Missionary</option>
                    <option value="western">Western Missionary</option>
                    <option value="international">International</option>
                    <option value="weekend" v-if="isAdminRoute">Weekend</option>
                    <option value="other" v-if="isAdminRoute">Other</option>
                </select>
            </template>
            <template v-else>
                <h5>{{ designation.content }}</h5>
            </template>

            <hr class="divider lg">
            <p class="small" v-text="moreInfo"></p>
        </div>
        <div class="panel-footer text-right" v-if="editMode">
            <hr class="divider sm inv">
            <button class="btn btn-default" @click="cancel()">Cancel</button>
            <button class="btn btn-primary" @click="save()">Save Changes</button>
            <hr class="divider sm inv">
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
            },
            'locked': {
                type: Boolean,
                default: false
            }
        },
        computed: {
            designation: {
                get() {
                    if (this.document) {
                        return this.document
                    } else {
                        return {content: ["eastern"]};
                    }
                },
                set() {}
            }
        },
        data() {
            return {
                editMode: true,
                moreInfo: '',
                eastern: 'Missionaries traveling from the Eastern, Central or Mountain US timezones. Miami registration at the Miami Airport Marriott Campus will open to you at 8am on July 22. You will not be able to check into your hotel room until after training at the Fillmore is complete. Shuttles to the Fillmore begin at 9:30am and continue through 1pm. There will be a secured luggage drop where you can keep your luggage during the training event. Estimated hotel check-in will be available at 10pm on July 22. Check out is before 12pm noon on July 23. Eastern missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 22.',
                western: 'Missionaries traveling from the Pacific, Alaska, or Hawaiian timezones. Miami Registration at the Miami Airport Marriott campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and the start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre There will be a secured luggage drop where you can keep your luggage during your time at South Beach. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. Western Missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.',
                international: 'Missionaries traveling from a nation outside of the United States with the exception of the eastern territories of Canada. Miami Registration at the Miami Airport Marriott Campus will be opened to you between 12pm and 11pm on July 21. You may not be able to check into your hotel room until 4pm on July 21 but as International Missionaries, you will receive first preference on rooms available for early check-in. Check out is at 9am on July 22. You will board the first shuttles to South Beach beginning at 9:30am on July 22. The time between when you arrive to South Beach and start of the Training Event at the Fillmore Theater at 2pm is time to enjoy the shopping and beaches around the Fillmore Theatre. There will be a secured luggage drop where you can keep your luggage during the Training Event. Upon returning from the Training Event, you will depart on an early AM flight to Nicaragua between the hours of 12am and 6am on July 23. International missionaries will receive meals beginning with lunch at 12pm and dinner at 5pm on July 21 as well as breakfast, lunch and dinner on July 22.',
                weekend: 'This designation includes Missionaries who need Miami rooming Friday and Saturday or earlier; A Western Missionary who arrives on Friday but won\'t fly out to late Sunday; or VIPS who are coming in early and will not fly out until Sunday.',
                other: 'This designation is for any missionary who does not fall within any of the other designations.'
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
                } else if(this.designation.content == 'weekend') {
                    this.moreInfo = this.weekend;
                } else if(this.designation.content == 'other') {
                    this.moreInfo = this.other;
                } else {
                    this.moreInfo = '';
                }
            },
            save() {
                if(this.document) {
                    this.$http.delete('arrival-designations/' + this.document.id).then((response) => {
                        console.log('old removed');
                    });
                }
                this.$http.post('arrival-designations/', {
                    content: [this.designation.content],
                    reservation_id: this.reservationId,
                    type: 'arrival_designation'
                }).then((response) => {
                    this.designation = response.data.data;
                    this.editMode = false;
                    
                    swal('Nice Work!', 'Document has been added.', 'success', {
                      buttons: false,
                      timer: 3000,
                    }).then(window.location.reload());
                });
            },
            cancel() {
                this.editMode = false;
                if(this.document) {
                    this.designation = this.document
                }
            }
        },
        mounted() {
            if(this.document) {
                this.editMode = false;
            }

            this.updateInfo();
        }
    }
</script>