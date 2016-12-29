<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <h6 class="text-uppercase">
                    <i class="fa fa-plane"></i> Preferred Airports
                    <button class="btn btn-xs btn-default-hollow pull-right" @click="editMode = true" v-if="! editMode"><i class="fa fa-pencil"></i> Change</button>
                </h6>
                <hr class="divider lg">

                <p v-if="editMode">Please enter the top three preferred <a href="https://www.world-airport-codes.com/" target="_blank" class="text-primary">airport codes</a> where would like to arrive.</p>
                <div class="row">
                    <div class="col-sm-4">
                        <label>1st Choice</label>
                        <input type="text" v-model="choice_one"
                               class="form-control" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <span class="help-block" v-if="editMode">Enter a three letter IATA code.</span>
                        <p v-else>{{ choice_one | uppercase }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label>2nd Choice</label>
                        <input type="text" v-model="choice_two"
                               class="form-control" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>{{ choice_two | uppercase }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label>3rd Choice</label>
                        <input type="text" v-model="choice_three"
                               class="form-control" maxlength="3"
                               minlength="3" style="text-transform: uppercase" v-if="editMode">
                        <p v-else>{{ choice_three | uppercase }}</p>
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
    export default{
        name: 'airport-preference',
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
        data() {
            return {
                editMode: true,
                choice_one: '',
                choice_two: '',
                choice_three: '',
                preference: {
                    content: []
                }
            }
        },
        computed: {
            formComplete: function() {
                if(this.choice_one.length == 3 && this.choice_two.length == 3 && this.choice_three.length == 3) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        methods: {
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
                    this.preference = response.data.data;
                    this.editMode = false;
                    this.setPreference(response.data.data);
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
                } else {
                    this.choice_one = null;
                    this.choice_two = null;
                    this.choice_three = null;
                }
            }
        },
        ready() {
            if(this.document) {
                this.editMode = false;
                this.choice_one = this.document.content[0];
                this.choice_two = this.document.content[1];
                this.choice_three = this.document.content[2];
            }
        }
    }
</script>