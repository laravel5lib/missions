<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-heartbeat"></i> {{medical.name}}</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/medical-releases/' + medical.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{medical.name}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>HAS CONDITION(S):</b> {{medical.has_conditions ? 'Yes' : 'No'}}
            <br>
            <b>HAS ALLERGY(S):</b> {{medical.has_allergies ? 'Yes' : 'No' }}
            <br>
            <b>LAST UPDATED:</b> {{medical.putd_at|moment('ll')}}
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
            medical: function() {
                return this.document;
            }
        }
    }
</script>