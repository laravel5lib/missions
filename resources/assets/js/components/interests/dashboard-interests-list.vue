<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Trip Interests</h5>
        </div>
    </div>

    <div class="">
        <div class="panel panel-default col-sm-6" v-for="interest in interests" :id="interest.id">
            <div class="panel-heading"><h6>{{ interest.name }}</h6></div>
            <div class="panel-body">
                <dl class="dl-hoizontal">
                    <dt>Email</dt>
                    <dd><a :href="interest.email ? ('mailto:' + interest.email) : '#'">{{ interest.email || 'None Provided'}}</a></dd>
                    <dt>Phone</dt>
                    <dd><a :href="interest.phone ? ('tel:' + interest.phone) : '#'">{{ interest.phone || 'None Provided'}}</a></dd>
                    <dt>Contact Pref.</dt>
                    <dd>{{ interest.communication_preferences}}</dd>
                    <dt>Interested since</dt>
                    <dd>{{ interest.created | moment 'll'}}</dd>
                </dl>
            </div>
        </div>
    </div>

        <!--<ul class="media-list col-sm-6">
            <div class="media" v-for="interest in interests" :id="interest.id">
                &lt;!&ndash;<div class="media-left">
                    <a href="#">
                        <img class="media-object" src="..." alt="...">
                    </a>
                </div>&ndash;&gt;
                <div class="media-body">
                    <h4 class="media-heading">{{ interest.name }}</h4>
                    <dl class="dl-horizontal">
                        <dt v-if="interest.email">Email</dt>
                        <dd v-if="interest.email"><a :href="'mailto:' + interest.email">{{ interest.email}}</a></dd>
                        <dt v-if="interest.phone">Phone</dt>
                        <dd v-if="interest.phone">{{ interest.phone}}</dd>
                        <dt>Contact Pref.</dt>
                        <dd>{{ interest.communication_preferences}}</dd>
                        <dt>Interested since</dt>
                        <dd>{{ interest.created | moment 'll'}}</dd>
                    </dl>
                </div>
            </div>
        </ul>-->
</template>
<script>
    export default{
        name: 'dashboard-interests-list',
        props: ['groupId', 'tripId'],
        data(){
            return{
                interests:[]
            }
        },
        ready() {
            this.$http.get('interests', {
                group: this.groupId,
                trip: this.tripId,
                include: ''
            }).then(function (response) {
                this.interests = response.data.data;
            })
        }
    }
</script>
