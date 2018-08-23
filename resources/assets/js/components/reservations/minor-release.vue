<template>
    <!-- Minor Release -->
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading">
            <h6>Minor Release</h6>
        </div>
        <div class="panel-body">
            <p>Anyone traveling under the age of 18 is required to have a legal guardian complete this form. This document must be <strong>completed, signed and notarized</strong>. Please mail the orginal (photocopies not accepted) notarized document to the below address:<p>

            <p>
                Missions.Me<br>
                34270 Pacific Coast Hwy.<br> 
                Suite C<br>
                Dana Point, CA 92629
            </p>

            <hr class="divider">

            <p><a class="btn btn-primary" href="/downloads/minors_release.pdf" target="_blank">
                <i class="fa fa-file-pdf-o icon-left"></i> Download Minor Release Form
            </a></p>
        </div>
    </div>
    <!-- end Minor Release -->
</template>
<script>
    export default {
        name: 'minor-release',
        props: {
            'document': {
                type: Object,
                required: false
            },
            'reservationId': {
                type: String,
                required: true
            }
        },
        data() {
            return {}
        },
        methods: {},
        mounted() {}
    }
</script>