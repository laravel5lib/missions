<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-envelope"></i> {{referral.type ? referral.type[0].toUpperCase() + referral.type.slice(1) : ''}} Reference</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/referrals/' + referral.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{referral.applicant_name}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>ATTENTION:</b> {{referral.attention_to}}
            <br>
            <b>STATUS:</b> {{referral.status ? referral.status[0].toUpperCase() + referral.status.slice(1) : ''}}
            <br>
            <b>LAST UPDATED:</b> {{referral.putd_at|moment('ll')}}
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
            referral: function() {
                return this.document;
            }
        }
    }
</script>