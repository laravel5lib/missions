<template>
	<div>
		<div>this is template body</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'travel-activity',
        mixins: [errorHandler],
        data(){
            return {
                name: 'Test Activity',
                description: 'Test Activity for Itineraries',
                occurred_at: '2017-04-19 14:54:33',

            }
        },
        methods: {},
        ready(){

        }
    }
</script>