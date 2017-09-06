<template>
	<div class="panel-group" :id="listId + listIndex" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default" v-for="(member, memberIndex) in members">
			<div class="panel-heading" role="tab" id="headingOne">
				<h4 class="panel-title">
					<div class="row">
						<div class="col-xs-9">
							<div class="media">
								<div class="media-left" style="padding-right:0;">
									<a :href="getReservationLink(member)" target="_blank">
										<img :src="member.avatar" class="img-circle img-xs av-left" style="margin-right: 10px">
										<slot name="leader" :member="member">
											<span style="position:absolute;top:-2px;left:4px;font-size:8px; padding:3px 5px;" class="badge" v-if="member && member.leader">GL</span>
										</slot>
									</a>
								</div>
								<div class="media-body" style="vertical-align:middle;">
									<h6 class="media-heading text-capitalize" style="margin-bottom:3px;">
										<i :class="getGenderStatusIcon(member)"></i>
										<a :href="getReservationLink(member)" target="_blank">{{ member.surname | capitalize }}, {{ member.given_names | capitalize }}</a></h6>
									<p style="line-height:1;font-size:10px;margin-bottom:2px;">{{ member.desired_role.name }} <span class="text-muted">&middot; {{ member.travel_group }}</span></p>
								</div><!-- end media-body -->
							</div><!-- end media -->
						</div>
						<div class="col-xs-3 text-right action-buttons">
							<slot name="action-buttons" :member="member">

							</slot>
							<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" :data-parent="'#' + listId" :href="'#memberItem' + listIndex + memberIndex" aria-expanded="true" aria-controls="collapseOne">
								<i class="fa fa-angle-down"></i>
							</a>
						</div>
					</div>
				</h4>
			</div>
			<div :id="'memberItem' + listIndex + memberIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
				<div class="panel-body">
					<slot name="details" :member="member">
						<div class="row">
						<div class="col-sm-6">
							<label>Gender</label>
							<p class="small">{{ member.gender | capitalize }}</p>
							<label>Marital Status</label>
							<p class="small">{{ member.status | capitalize }}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Age</label>
							<p class="small">{{member.age}}</p>
							<template v-if="member.trip && member.trip.data.group">
								<label>Travel Group</label>
								<p class="small">{{member.trip.data.group.data.name}}</p>
							</template>

						</div><!-- end col -->
						<div class="col-sm-12" v-if="member.companions">
							<label>Companions</label>
							<ul class="list-unstyled" v-if="member.companions.data.length">
								<li v-for="companion in member.companions.data">
									<i :class="getGenderStatusIcon(companion)"></i>
									{{ companion.surname | capitalize }}, {{ companion.given_names | capitalize }}
									<span class="text-muted">({{ companion.relationship | capitalize }})</span>
								</li>
							</ul>
							<p class="small" v-else>None</p>
						</div>
						<div class="col-sm-6" v-if="member.trip">
							<label>Trip Type</label>
							<p class="small">{{ member.trip.data.type | capitalize }}</p>
						</div>
						<div class="col-sm-6">
							<label>Designation</label>
							<p class="small">{{ member.arrival_designation }}</p>
						</div>
					</div><!-- end row -->
					</slot>
				</div><!-- end panel-body -->
			</div>
			<slot name="companions" :member="member">
				<div class="panel-footer small clearfix" style="background-color: #ffe000;" v-if="member.companions && member.companions.data.length && companionsPresentSquad(member, squad)">
					<i class=" fa fa-info-circle"></i> {{member.present_companions}} companions not in this group &middot; {{companionsPresentTeam(member)}} not on this squad.
					<button type="button" class="btn btn-xs btn-default-hollow pull-right" @click="addCompanionsToSquad(member, squad)"><i class="fa fa-plus-circle"></i> Companions</button>
				</div>
			</slot>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    export default {
        name: 'members-list-slot',
	    props: {
            members: {
                type: Array,
	            required: true
            },
            'list-id': {
                type: String
            },
            'list-index': {
                type: Number,
	            default: Number(_.uniqueId())
            },
	    },
        data() {
            return {

            }
        },
        methods: {
            getReservationLink(reservation){
                return `${this.isAdminRoute ? '/admin' : '/dashboard'}/reservations/${reservation.id}`;
            },
            getGenderStatusIcon(reservation){
                switch (reservation.gender) {
                    case 'male':
                        if (reservation.status == 'married') {
                            return 'fa fa-venus-mars text-info';
                        }
                        return 'fa fa-mars text-info';
                    case 'female':
                    default:
                        if (reservation.status == 'married') {
                            return 'fa fa-venus-mars text-danger';
                        }
                        return 'fa fa-venus text-danger';
                }
            },
        },
        mounted() {

        }
    }
</script>