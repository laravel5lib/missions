<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-camera-retro"></i> {{credential.applicant_name}}</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/media-credentials/' + medical.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{credential.applicant_name}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>LAST UPDATED:</b> {{credential.updated_at|moment 'lll'}}
        </p>
    </div>
</template>
<script>
    export default{
        props: {
            'document': {
                type: Object,
                required: true
            }
        },
        computed: {
            credential: function() {
                return this.document;
            }
        }
    }
</script>