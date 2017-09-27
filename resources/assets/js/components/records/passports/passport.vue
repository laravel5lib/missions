<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{passport.citizenship_name}}</h6>
        <a role="button" :href="'/' + firstUrlSegment + '/records/passports/' + passport.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{passport.given_names}} {{passport.surname}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>NUMBER:</b> {{passport.number}}
            <br>
            <b>EXPIRES ON:</b> {{passport.expires_at|moment('ll')}}
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
            passport: function() {
                return this.document;
            }
        }
    }
</script>