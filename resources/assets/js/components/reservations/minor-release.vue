<template>
    <!-- Minor Release -->
    <div>
        <div class="row">
            <div class="col-xs-12">
                <h6 class="text-uppercase">Minor Release Form</h6>
                <hr class="divider lg">

                <p>Anyone traveling under the age of 18 is required to have a legal guardian complete this form. This document must be <strong>completed, signed and notarized</strong>. Please mail the orginal (photocopies not accepted) notarized document to the below address:<p>

                <p>
                    Missions.Me</br>
                    145 S. Livernois Rd.<br>
                    Suite 308<br>
                    Rochester Hills, MI 48307
                </p>

                <p class="text-center"><a class="btn btn-primary btn-sm" href="/downloads/minors_release.pdf" target="_blank">
                    <i class="fa fa-file-pdf-o icon-left"></i> Download Minor Release Form
                </a></p>
            </div>
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
        ready() {}
    }
</script>