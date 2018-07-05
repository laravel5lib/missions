<template>
    <ajax-form 
        method="post" 
        action="squad-members" 
        :initial="{ reservation_ids: reservationIds, squad_id: null, group: null}" 
        :horizontal="true"
        ref="ajax" 
        @form:success="$emit('successful:form')"
    >
        <div class="panel-body" slot-scope="{ form }">

            <div class="alert alert-warning" style="display:flex; justify-content: flex-start; align-items: center;">
                <div><i class="fa fa-info-circle fa-lg" style="margin-right: 1em"></i></div>
                <div>Adding <strong>{{ members.length }} missionaries.</strong></div>
            </div>

            <input-select name="region_id" v-model="selectedRegionId" :horizontal="true" classes="col-md-6" :options="regions">
                <label slot="label" class="control-label col-md-4">Region</label>
                <span slot="help-text" class="help-block" v-if="!regions">
                    No regions found. <a :href="`/admin/campaigns/${campaignId}/regions/create`">Create a region</a>.
                </span>
            </input-select>

            <input-select name="squad_id" v-model="selectedSquadId" :horizontal="true" classes="col-md-6" :options="squads">
                <label slot="label" class="control-label col-md-4">Squad</label>
                <span slot="help-text" class="help-block" v-if="!squads">
                    No squads found. Try a different region or <a :href="`/admin/campaigns/${campaignId}/reservations/squads/create`">add a squad</a>.
                </span>
            </input-select>
            
            <input-text name="group" v-model="form.group" :horizontal="true" classes="col-md-6">
                <label slot="label" class="control-label col-md-4">Group (optional)</label>
            </input-text>

            <template v-if="companions.length">
                <div class="alert alert-danger" 
                     style="display:flex; justify-content: flex-start; align-items: center;"
                >
                    <div><i class="fa fa-info-circle fa-lg" style="margin-right: 1em"></i></div>
                    <div><strong>{{ companions.length }} companion(s)</strong> could not be found in the selected squad. Select the companions you wish to add/move to this squad.</div>
                </div>
                <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th>Surname</th>
                            <th>Given Names</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Relationship</th>
                            <th>Role</th>
                            <th>Organization</th>
                            <th>Trip Type</th>
                            <th>Group</th>
                            <th>Squad</th>
                            <th>Region</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="companion in companions" 
                            :key="companion.id" 
                            :class="{ 'selected' : isSelected(companion) }"
                        >
                            <td>
                                <input 
                                    type="checkbox" 
                                    :checked="isSelected(companion)" 
                                    @change="select(companion, $event.target.checked)"
                                >
                            </td>
                            <td>{{ companion.surname }}</td>
                            <td>{{ companion.given_names }}</td>
                            <td>{{ companion.gender }}</td>
                            <td>{{ companion.age }}</td>
                            <td>{{ companion.relationship }}</td>
                            <td>{{ companion.role.name }}</td>
                            <td>{{ companion.trip.group.name }}</td>
                            <td>{{ companion.trip.type }}</td>
                            <td>{{ companion.squad.group }}</td>
                            <td>{{ companion.squad.callsign }}</td>
                            <td>{{ companion.squad.region }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </template>

            <hr class="divider">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <button class="btn btn-link" @click.prevent="$emit('cancelled:form')">Cancel</button>
                    <button type="submit" class="btn btn-primary" :disabled="!selectedRegionId || !selectedSquadId">Add to Squad</button>
                </div>
            </div>
        </div>
    </ajax-form>
</template>

<script>
export default {
    props: {
        campaignId: {
            type: String,
            required: true
        },
        reservations: {
            type: Array,
            required: true
        }
    },

    computed: {
        reservationIds() {
            return _.pluck(this.members, 'id');
        }
    },

    data() {
        return {
            selectedRegionId: null,
            regions: null,
            squads: null,
            selectedSquadId: null,
            companions: [],
            members: this.reservations,
            selected: []
        }
    },

    watch: {
        selectedRegionId(value) {
            if (value) {
                this.getSquads();
            } else {
                this.squads = null;
                this.selectedSquadId = null;
            }
        },
        selectedSquadId(value) {
            this.$refs.ajax.form.squad_id = value;
            this.$forceUpdate();
            if (value) {
                this.getCompanions();
            } else {
                this.companions = []
            }
        }
    },

    methods: {
        select(item, value) {
            if (value) {
                this.members.push(item);
                this.$refs.ajax.form.reservation_ids.push(item.id);
            } else {
                this.members = _.without(this.members, item);
                this.$refs.ajax.form.reservation_ids = _.reject(this.$refs.ajax.form.reservation_ids, function (id) {
                    return id == item.id
                });
            }
        },

        isSelected(item) {
            return _.findWhere(this.members, item)
        },

        getRegions() {
            this.$http.get(`regions?filter[campaign_id]=${this.campaignId}`).then(response => {
                let regions = {'': '-- select a region --'};
                _.each(response.data.data, function(region) {
                    regions[region.id] = region.name;
                });
                this.regions = regions;
            }).catch(error => {
                console.log('oops! something went wrong!');
            });
        },

        getSquads() {
            this.$http.get(`squads?filter[region_id]=${this.selectedRegionId}`).then(response => {
                let squads = {'': '-- select a squad --'};
                _.each(response.data.data, function(squad) {
                    squads[squad.id] = squad.callsign;
                });
                this.squads = squads;
            }).catch(error => {
                console.log('oops! something went wrong!');
            });
        },

        getCompanions() {
            let ids = _.pluck(this.reservations, 'id').join();
            let that = this;

            this.$http.get(`companions?filter[reservation_id]=${ids}&filter[not_in_squad]=${this.selectedSquadId}&include=companion-reservation.trip.group,companion-reservation.squad-memberships.squad.region&per_page=100`)
                .then(response => {
                    let companions = _.reject(response.data.data, function (companion) {
                        return _.findWhere(that.reservations, {id: companion.id});
                    });

                    // remove duplicates because they may be companions 
                    // to more than one selected reservation
                    this.companions = _.uniq(companions, 'id');
                })
                .catch(error => {
                    console.log('oops! Unable to get companions!')
                });
        }
    },

    mounted() {
        this.getRegions();
    }
}
</script>

<style>

</style>
