<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{visa.country_name}}</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/visas/' + visa.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{visa.given_names}} {{visa.surname}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>NUMBER:</b> {{visa.number}}
            <br>
            <b>ISSUED ON:</b> {{visa.issued_at|moment('ll')}}
            <br>
            <b>EXPIRES ON:</b> {{visa.expires_at|moment('ll')}}
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
            visa: function() {
                return this.document;
            }
        }
    }
</script>