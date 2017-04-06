<template>
    <div>
        <p>List Goes here</p>
    </div>
</template>
<script type="text/javascript">
    export default {
        name: 'promotional-list',
        props: {
          'type': {
            type: String,
            required: true
          },
          'id': {
            type: String,
            required: true
          }
        },
        data() {
            return {
                promos: []
            }
        },
        methods: {
            fetch() {
                return console.log('fetch promotionals');
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>