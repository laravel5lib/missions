<template>
    <div class="row">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-sm-12">
			<form class="form-inline text-right" novalidate>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search (name, amount)">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">Sort:</span>
					<select class="form-control" v-model="orderByField">
						<option value="name">Name</option>
						<option value="amount">Amount</option>
						<option value="date">Date</option>
					</select>
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">Order</span>
					<select class="form-control" v-model="direction">
						<option :value="1">Desc.</option>
						<option :value="-1">Asc.</option>
					</select>
				</div>
			</form>
			<hr>
        </div>
		<div class="col-sm-12">
			<div class="media" v-for="donation in donations|filterBy search|orderBy orderByField direction">
                <a class="media-left" href="#">
                    <img src="http://placehold.it/64x64" style="width: 64px; height: 64px;">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{ donation.name }} <small>donated</small> {{ donation.amount|currency }} <small class="pull-right">{{ donation.created_at|moment }}</small></h4>
                    <p><b>Message</b>: {{ donation.message }}</p>
                </div>
            </div>
        </div>

    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'donations-list',
        props: {
            fundraiserId: String,
            donations: {
                default: [],
                coerce: function (val) {
                    return JSON.parse(val);
                }
            }
        },
		data(){
			return{
				search: '',
				orderByField: 'name',
				direction: 1,
			}
		},
		watch:{
            'donations': function () {

            }
        },
        methods: {
            getDonations(){
				this.$refs.spinner.show();
				this.$http.get('fundraisers.donations', {

                }).then(function (response) {
                    this.donations = response.data.data;
					this.$refs.spinner.hide();
				})
            },
        },
        ready(){
            //this.getDonations();
        }
    }
</script>
