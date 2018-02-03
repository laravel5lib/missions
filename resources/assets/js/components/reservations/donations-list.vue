<template>
    <div class="row">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="col-sm-12">
			<form class="form-inline text-right" novalidate>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search (name, amount)">
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
			<div class="media" v-for="donation in donationsOrderedFiltered">
                <a class="media-left" href="#">
                    <img src="http://placehold.it/64x64" style="width: 64px; height: 64px;">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{{ donation.name }} <small>donated</small> {{ currency(donation.amount)}} <small class="pull-right">{{ donation.created_at|moment }}</small></h4>
                    <p><b>Message</b>: {{ donation.message }}</p>
                </div>
            </div>
        </div>

    </div>
</template>

<script type="text/javascript">
    import _ from 'underscore';
    export default{
        name: 'donations-list',
        props: {
            fundraiserId: String,
            donations: {
                default: [],
                coerce(val, oldVal) {
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
	    computed: {
            donationsOrderedFiltered() {
                let arr = _.filter(this.donations, (donation) => {
                    return donation.name.includes(this.search)
                });
	            arr = _.sortBy(arr, this.orderByField);
                return this.direction === -1 ? arr.reverse() : arr;
            }
	    },
		watch:{
            donations() {

            }
        },
        methods: {
            debouncedSearch: _.debounce(function() {
                this.getDonations();
            },250),
            getDonations(){
				// this.$refs.spinner.show();
				this.$http.get('fundraisers.donations', { params: {
					search: this.search
                }}).then((response) => {
                    this.donations = response.data.data;
					// this.$refs.spinner.hide();
				})
            },
        },
        mounted(){
            //this.getDonations();
        }
    }
</script>
