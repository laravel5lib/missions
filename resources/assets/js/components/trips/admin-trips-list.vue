<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                        <span class="input-group-addon">
                           <i class="fa fa-search"></i>
                        </span>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
                    | <a class="btn btn-primary btn-sm" href="trips/create"><i class="fa fa-plus"></i> New</a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover">
            <thead>
            <tr>
                <th :class="{'text-primary': orderByField === 'group.data.name'}">
                    Group
                    <i @click="setOrderByField('group.data.name')" v-if="orderByField !== 'group.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'campaign.data.name'}">
                    Campaign
                    <i @click="setOrderByField('campaign.data.name')" v-if="orderByField !== 'campaign.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'published_at'}">
                    Published
                    <i @click="setOrderByField('published_at')" v-if="orderByField !== 'published_at'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'published_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th>
                    Start &amp; End
                </th>
                <th><i class="fa fa-plane"></i></th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="trip in trips|filterBy search|orderBy orderByField direction">
                <td>{{trip.group.data.name}}</td>
                <td>{{trip.type|capitalize}}</td>
                <td>{{trip.campaign.data.name|capitalize}}</td>
                <td>{{trip.published_at|moment 'lll'}}</td>
                <td>{{trip.started_at|moment 'll'}} - <br>{{trip.ended_at|moment 'll'}}</td>
                <td>{{trip.reservations}}</td>
                <td>
                    <a href="/admin{{trip.links[0].uri}}"><i class="fa fa-eye"></i></a>
                    <a href="/admin{{campaignId + trip.links[0].uri}}/edit"><i class="fa fa-pencil"></i></a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default{
        name: 'admin-trips',
        data(){
            return{
                trips: [],
                orderByField: 'group.data.name',
                direction: 1,
                search: null
            }
        },
        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'group.data.name';
                this.direction = 1;
                this.search = null;
            }
        },
        ready(){
            this.$http.get('trips', { include:'campaign,group'}).then(function (response) {
                this.trips = response.data.data;
            })
        }
    }
</script>
