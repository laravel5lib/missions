<template>
	<div class="panel panel-default">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="panel-heading">
			<h5>Newest Reservations</h5>
		</div>
		<table class="table table-hover">
			<tbody>
			@foreach($reservations->current()->latest()->take(5)->get() as $reservation)
			<tr onclick="window.location.href = 'admin/reservations/{{ $reservation->id }}'" style="cursor: pointer;">
				<td style="padding:5px 15px;vertical-align:middle;font-size:12px;">{{ $reservation->given_names }}</td>
				<td style="padding:5px 15px;vertical-align:middle;font-size:12px;">
					{{ $reservation->trip->campaign->name }}
					<br /><small class="text-muted">{{ $reservation->trip->group->name }}</small>
				</td>
				<td style="padding:5px 15px;vertical-align:middle;line-height:12px;">
					<small class="text-muted" style="font-size:10px;">{{ $reservation->created_at->diffForHumans() }}</small>
				</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		<div class="panel-footer text-center" style="padding:10px;">
			<a class="small" style="color:#bcbcbc;" :href=" url('/admin/reservations/current') ">View All Reservations</a>
		</div>
	</div>
</template>
<script type="text/javascript">
    export default{
    	name: 'dashboard-reservations',
        data(){
            return{
                reservations: []
            }
        },
		methods: {
            getReservations() {
				this.$http.get('reservations', { params: {
					current: true,
					per_page: 5,
					sort: 'created_at+DESC',
					page: 1
				}}).then((response) => {
					this.reservations = response.data.data;
				})
			}
		},
        mounted(){
            this.getReservations();
        }
    }

</script>