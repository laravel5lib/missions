<template>
	<div class="row" style="position:relative;">
		<div class="col-md-8">
			{{ currentTeam.name }}
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#members" data-toggle="pill"><i class="fa fa-user"></i> Members</a>
				</li>
				<li role="presentation">
					<a href="#teamdetails" data-toggle="pill"><i class="fa fa-list"></i> Team Details</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="members">

				</div>
				<div role="tabpanel" class="tab-pane" id="teamdetails">
					<div class="form-group">
						<label for="">Name</label>
						<input type="text" class="form-control"  placeholder="Name" v-model="currentTeam.name">
					</div>

					<div class="form-group">
						<label for="" class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
							<select class="form-control" v-model="currentTeam.type">
								<option value=""></option>
							</select>
						</div>
					</div>

					<hr class="divider sm">

				</div>
			</div>
		</div>
		<div class="col-md-4">
			<ul class="nav nav-tabs">
				<li role="presentation" class="active">
					<a href="#reservations" data-toggle="pill"><i class="fa fa-ticket"></i> Reservations</a>
				</li>
				<li role="presentation">
					<a href="#teams" data-toggle="pill"><i class="fa fa-group"></i> Teams</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="reservations">

				</div>
				<div role="tabpanel" class="tab-pane" id="teams">
					<div class="row">
						<div class="col-xs-12 text-right">
							<button class="btn btn-primary" @click="newTeam">New Team</button>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    export default{
        name: 'team-manager',
	    props: ['groupId'],
        data(){
            return {
                teams: [],
	            currentTeam: null,
	            group: null,
            }
        },
        methods: {
            getTeams(){
                this.$http.get('groups/' + this.groupId + '/teams', { params: { }}).then(function (response) {
	                this.teams = response.body.data;
                });
            },
	        newTeam(){
                let team = {
                    name: 'Team #' + (this.teams.length + 1),
	                type: 'default',
                }
	        }
        },
        ready(){

        }
    }
</script>