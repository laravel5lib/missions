<template>
    <div>
        <h6 class="text-uppercase"><i class="fa fa-file-text"></i> {{essay.subject}}</h6>
        <a role="button" :href="'/dashboard/records/essays/' + essay.id">
            <h5 style="text-transform:capitalize;" class="text-primary">
                {{essay.author_name}}
            </h5>
        </a>
        <hr class="divider lg">
        <p class="small">
            <b>QUESTIONS:</b> {{essay.content.length}}
            <br>
            <b>LAST UPDATED:</b> {{essay.updated_at|moment 'll'}}
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
            essay: function() {
                return this.document;
            }
        }
    }
</script>