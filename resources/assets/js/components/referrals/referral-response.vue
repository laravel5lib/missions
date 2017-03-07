<template>
    <section>
        <spinner v-ref:spinner size="xl" :fixed="false" text="Loading..."></spinner>
        <validator name="validation" :classes="{ invalid: 'has-error' }">
        <div class="form-group" v-for="item in referral.response" :class="{ 'has-error' : $validation.item.invalid}">
            <label for="item_{{ $index }}">{{ item.q }}</label>

            <span v-if="item.type == 'radio'">
                <p>
                    <input type="radio" value="yes" v-model="item.a"> Yes
                    <input type="radio" value="no" v-model="item.a"> No
                </p>
            </span>
            <span v-else>
                <textarea id="item_{{ $index }}" class="form-control" v-model="item.a" rows="5" initial="off" v-validate:item="{required: true}"></textarea>
            </span>
        </div>
        </validator>
        <div class="form-group text-center">
            <button class="btn btn-default" @click="reset">Clear</button>
            <button class="btn btn-primary" @click="save">Submit</button>
        </div>
    </section>
</template>
<script>
    export default{
        name: 'referral-response',
        props: {
            'id': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                referral: {}
            }
        },
        methods: {
            fetch() {
                this.$http.get('referrals/' + this.id).then(function (response) {
                    this.referral = response.body.data;
                },function () {
                    this.$dispatch('showError', 'Unable to retrieve the referral request!')
                });
            },
            reset() {
                this.referral = {}
                this.fetch();
            },
            save() {
                // validate manually
                let self = this;
                this.$validate(true, function () {
                    if (self.$validation.invalid) {
                        console.log('validation errors');
                        self.$dispatch('showError', 'Could not submit. Please check the form.');
                    } else {
                        self.referral.responded_at = moment().format('YYYY-MM-DD HH:MM:SS');
                        self.$http.put('referrals/' + self.id, self.referral).then(function (response) {
                            self.$dispatch('showSuccess', 'Thank you for submitting your response.');
                        },function () {
                            self.$dispatch('showError', 'Unable to retrieve the referral request!');
                        });
                    }
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>