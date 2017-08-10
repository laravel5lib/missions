<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <h6 class="text-uppercase">
                    <i class="fa fa-plane"></i> Preferred Airports
                    <button class="btn btn-xs btn-default-hollow pull-right" @click="editMode = true" v-if="! editMode && ! isLocked"><i class="fa fa-pencil"></i> Change</button>
                </h6>
                <hr class="divider lg">

                <!--<p v-if="editMode">Please enter the <a href="https://www.world-airport-codes.com/" target="_blank" class="text-primary">airport codes</a> of your top three preferred arrival locations.</p>-->
                <div class="row">
                    <div class="col-sm-4">
                        <label>1st Choice</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  :value.sync="choiceOneObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_one"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_one.toUpperCase() }}) <span v-if="choiceOneObj">{{ choiceOneObj.name.toUpperCase() }}</span></p>
                    </div>
                    <div class="col-sm-4">
                        <label>2nd Choice</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  :value.sync="choiceTwoObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_two"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_two.toUpperCase() }}) <span v-if="choiceTwoObj">{{ choiceTwoObj.name.toUpperCase() }}</span></p>
                    </div>
                    <div class="col-sm-4">
                        <label>3rd Choice</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  :value.sync="choiceThreeObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_three"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_three.toUpperCase() }}) <span v-if="choiceThreeObj">{{ choiceThreeObj.name.toUpperCase() }}</span></p>
                    </div>
                </div>
                <div class="row" v-if="editMode">
                    <div class="col-sm-12 text-center">
                        <hr class="divider inv">
                        <button class="btn btn-default" @click="cancel()">Cancel</button>
                        <button class="btn btn-primary" @click="save()" :disabled="formComplete">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    export default{
        name: 'airport-preference',
        components: {vSelect},
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
        data() {
            return {
                editMode: true,
                choiceOneObj: null,
                choiceTwoObj: null,
                choiceThreeObj: null,
                choice_one: '',
                choice_two: '',
                choice_three: '',
                preference: {
                    content: []
                },
                airportsOptions: []
            }
        },
        watch: {
            choiceOneObj(val, oldVal){
                if (val && val.iata)
                    this.choice_one = val.iata;
            },
            choiceTwoObj(val, oldVal){
                if (val && val.iata)
                    this.choice_two = val.iata;
            },
            choiceThreeObj(val, oldVal){
                if (val && val.iata)
                    this.choice_three = val.iata;
            },
        },
        computed: {
            formComplete: function() {
                return !(!!this.choice_one && !!this.choice_two && !!this.choice_three);
            },
            'isLocked': function() {
                if (this.isAdminRoute)
                    return false;

                return this.locked;
            }
        },
        methods: {
            getAirports(search, loading){
                loading ? loading(true) : void 0;
                return this.$http.get('utilities/airports', { params: {search: search, sort: 'name'} }).then(function (response) {
                    this.airportsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.airportsOptions;
                    }
                });
            },
            getAirport(reference){
                return this.$http.get('utilities/airports/' + reference).then(function (response) {
                    return response.body.data;
                });
            },
            save() {
                if(this.document) {
                    this.$http.delete('questionnaires/' + this.document.id).then(function (response) {
                        console.log('old removed');
                    });
                }
                this.$http.post('questionnaires/', {
                    content: [this.choice_one, this.choice_two, this.choice_three],
                    reservation_id: this.reservationId,
                    type: 'airport_preference'
                }).then(function (response) {
                    this.preference = response.body.data;
                    this.editMode = false;
                    this.setPreference(response.body.data);
                });
            },
            setPreference(preference) {
              this.$dispatch('set-document', preference);
            },
            cancel() {
                this.editMode = false;
                if(this.document) {
                    this.choice_one = this.document.content[0];
                    this.choice_two = this.document.content[1];
                    this.choice_three = this.document.content[2];
                    this.getAirport(this.choice_one).then(function (response) {
                        this.choiceOneObj = response;
                    });
                    this.getAirport(this.choice_two).then(function (response) {
                        this.choiceTwoObj = response;
                    });
                    this.getAirport(this.choice_three).then(function (response) {
                        this.choiceThreeObj = response;
                    });
                } else {
                    this.choice_one = null;
                    this.choice_two = null;
                    this.choice_three = null;
                }
            }
        },
        mounted() {
            if(this.document) {
                this.editMode = false;
                this.choice_one = this.document.content[0];
                this.choice_two = this.document.content[1];
                this.choice_three = this.document.content[2];
                this.getAirport(this.choice_one).then(function (response) {
                    this.choiceOneObj = response;
                });
                this.getAirport(this.choice_two).then(function (response) {
                    this.choiceTwoObj = response;
                });
                this.getAirport(this.choice_three).then(function (response) {
                    this.choiceThreeObj = response;
                });
            }
        }
    }
</script>