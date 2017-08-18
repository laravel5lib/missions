<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-envelope"></i> {{ referral.type|capitalize }} Reference</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/referrals/' + referral.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{referral.applicant_name}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>ATTENTION:</b> {{referral.attention_to}}
            <br>
            <b>STATUS:</b> {{ referral.status|capitalize }}
            <br>
            <b>LAST UPDATED:</b> {{referral.updated_at|moment('ll')}}
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