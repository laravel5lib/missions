<template>
    <section>
        <spinner ref="spinner" global size="xl" :fixed="false" text="Loading..."></spinner>

        <div class="form-group" v-for="(item, index) in referral.response" :class="{ 'has-error' : errors.has('item')}">
            <label :for="'item_' + index">{{ item.q }}</label>

            <span v-if="item.type == 'radio'">
                <p>
                    <input type="radio" name="item" value="yes" v-model="item.a"> Yes
                    <input type="radio" name="item" value="no" v-model="item.a"> No
                </p>
            </span>
            <span v-else>
                <textarea :id="'item_' + index" class="form-control" v-model="item.a" rows="5"  name="item" v-validate="'required'"></textarea>
            </span>
        </div>

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
                this.$http.get('referrals/' + this.id).then((response) => {
                    this.referral = response.data.data;
                },() =>  {
                    this.$root.$emit('showError', 'Unable to retrieve the referral request!')
                });
            },
            reset() {
                this.referral = {}
                this.fetch();
            },
            save() {
                // validate manually
                let self = this;
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        console.log('validation errors');
                        self.$root.$emit('showError', 'Could not submit. Please check the form.');
                    } else {
                        self.referral.responded_at = moment().format('YYYY-MM-DD HH:mm:ss');
                        self.$http.put('referrals/' + self.id, self.referral).then((response) => {
                            self.$root.$emit('showSuccess', 'Thank you for submitting your response.');
                        },() =>  {
                            self.$root.$emit('showError', 'Unable to retrieve the referral request!');
                        });
                    }
                });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>