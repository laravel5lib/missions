<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="UpdateReservation">
        <form id="UpdateReservation" novalidate class="form-horizontal">
            <div class="form-group" :class="{ 'has-error': checkForError('givennames') || checkForError('surname')  }">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="given_names">Given Names</label>
                        <input type="text" class="form-control" name="given_names" id="given_names" v-model="given_names"
                               placeholder="Reservation Name" v-validate:givennames="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="100" minlength="1" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" name="surname" id="surname" v-model="surname"
                               placeholder="Reservation Name" v-validate:surname="{ required: true, minlength:1, maxlength:100 }"
                               maxlength="100" minlength="1" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label>Gender</label>
                    <div class="radio" :class="{ 'has-error': checkForError('gender') }">
                        <label>
                            <input type="radio" v-model="gender" v-validate:gender="{ required: { rule: true} }"
                                   value="male"> Male
                        </label>
                    </div>
                    <div class="radio" :class="{ 'has-error': checkForError('gender') }">
                        <label>
                            <input type="radio" v-model="gender" v-validate:gender value="female"> Female
                        </label>
                    </div>
                    <span class="help-block" v-show="checkForError('gender')">Select a gender</span>

                </div>
                <div class="col-sm-6">
                    <div class="form-group" :class="{ 'has-error': checkForError('relationshipStatus') }">
                        <label for="infoRelStatus">Relationship Status</label>
                        <select class="form-control input-sm" v-model="relationshipStatus"
                                v-validate:relationshipStatus="{ required: true }" :classes="{ invalid: 'has-error' }" id="infoRelStatus">
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>Date of Birth</label>
                    <datepicker :value.sync="birthday" :clear-button="true" :placeholder="Date of Birth"></datepicker>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" :class="{ 'has-error': checkForError('size') }">
                        <label for="infoShirtSize">Shirt Sizes</label>
                        <select class="form-control input-sm" v-model="shirt_size" v-validate:size="{ required: true }" :classes="{ invalid: 'has-error' }"
                                id="infoShirtSize">
                            <option value="S">S (Small)</option>
                            <option value="M">M (Medium)</option>
                            <option value="L">L (Large)</option>
                            <option value="XL">XL (Extra Large)</option>
                            <option value="XXL">XXL (2 Extra Large)</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </validator>
</template>
<script>
    import vSelect from 'vue-select';
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    export default{
        name: 'admin-reservation-edit',
        props: ['id'],
        components: { vSelect, 'datepicker': VueStrap.datepicker },
        data(){
            return{
                given_names: '',
                surname: '',
                gender: '',
                birthday: '',
                shirt_size: '',

                // logic vars
                attemptSubmit: false,
                resource: this.$resource('reservations{/id}', {id: this.id})
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UpdateReservation[field].invalid && this.attemptSubmit;
            },

        },
        ready(){
            this.resource.get().then(function (response) {
                var reservation = response.data.data;
                this.given_names = reservation.given_names;
                this.surname = reservation.surname;
                this.gender = reservation.gender;
                this.birthday = moment(reservation.birthday).toDate();
                this.shirt_size = reservation.shirt_size;
            })
        }
    }
</script>
