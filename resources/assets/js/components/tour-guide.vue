<template>
	<div>
		<div>this is template body</div>
	</div>
</template>
<style scoped></style>
<script type="text/javascript">
    export default{
        name: 'tour-guide',
        data(){
            return {
                steps: [
                    {
                        element: "#test-element",
                        title: "Here we go!",
                        content: "Content of this step"
                    }
                ]
            }
        },
        methods: {},
        ready(){
            // Initialize the tour
	        if (!localStorage.getItem('TourComplete')) {
	            let tour = new Tour(this.steps);

                tour.init();

                // Start the tour
                tour.start();
            }
        }
    }
</script>