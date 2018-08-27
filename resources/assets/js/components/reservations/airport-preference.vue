<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">

        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>Preferred Airports</h5>
                </div>
                <div class="col-xs-4 text-right">
                    <button class="btn btn-sm btn-link" @click="editMode = true" v-if="! editMode && ! isLocked">
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <div class="list-group">
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-4 text-muted text-right">
                        1st Choice
                    </div>
                    <div class="col-xs-8">
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  v-model="choiceOneObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_one"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_one.toUpperCase() }}) <span v-if="choiceOneObj">{{ choiceOneObj.name }}</span></p>
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-4 text-muted text-right">
                        2nd Choice
                    </div>
                    <div class="col-xs-8">
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  v-model="choiceTwoObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_two"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_two.toUpperCase() }}) <span v-if="choiceTwoObj">{{ choiceTwoObj.name }}</span></p>
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="row">
                    <div class="col-xs-4 text-muted text-right">
                        3rd Choice
                    </div>
                    <div class="col-xs-8">
                        <v-select @keydown.enter.prevent=""  class="form-control" id="airportFilter" :debounce="250" :on-search="getAirports"
                                  v-model="choiceThreeObj" :options="airportsOptions" label="name"
                                  placeholder="Search Airports" v-if="editMode"></v-select>
                        <input type="text" v-model="choice_three"
                               class="form-control hidden" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>({{ choice_three.toUpperCase() }}) <span v-if="choiceThreeObj">{{ choiceThreeObj.name }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer" v-if="editMode">
            <div class="row">
                <div class="col-sm-12 text-right">
                    <hr class="divider sm inv">
                    <button class="btn btn-link" @click="cancel()">Cancel</button>
                    <button class="btn btn-primary" @click="save()" :disabled="formComplete">Save Changes</button>
                    <hr class="divider sm inv">
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
                return this.$http.get('utilities/airports', { params: {search: search, sort: 'name'} }).then((response) => {
                    this.airportsOptions = response.data.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return this.airportsOptions;
                    }
                });
            },
            getAirport(reference){
                return this.$http.get('utilities/airports/' + reference).then((response) => {
                    return response.data.data;
                });
            },
            save() {
                if(this.document) {
                    this.$http.delete('airport-preferences/' + this.document.id).then((response) => {
                        console.log('old removed');
                    });
                }
                this.$http.post('airport-preferences/', {
                    content: [this.choice_one, this.choice_two, this.choice_three],
                    reservation_id: this.reservationId,
                    type: 'airport_preference'
                }).then((response) => {
                    this.preference = response.data.data;
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
                    this.choice_one = this.document.content[0];
                    this.choice_two = this.document.content[1];
                    this.choice_three = this.document.content[2];
                    this.getAirport(this.choice_one).then((response) => {
                        this.choiceOneObj = response;
                    });
                    this.getAirport(this.choice_two).then((response) => {
                        this.choiceTwoObj = response;
                    });
                    this.getAirport(this.choice_three).then((response) => {
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
                this.getAirport(this.choice_one).then((response) => {
                    this.choiceOneObj = response;
                });
                this.getAirport(this.choice_two).then((response) => {
                    this.choiceTwoObj = response;
                });
                this.getAirport(this.choice_three).then((response) => {
                    this.choiceThreeObj = response;
                });
            }
        }
    }
</script>