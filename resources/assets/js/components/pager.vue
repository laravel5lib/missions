<template>
<nav>
    <ul class="pager">
        <li class="previous" 
            :class="{ 'disabled' : pagination.current_page <= 1 }">
                <a @click.prevent="previousPage()">
                    <span aria-hidden="true">&larr;</span> Previous
                </a>
        </li>
        <li class="small" v-if="showPageCount">Page {{ pagination.current_page }} of {{ pagination.last_page }}</li>
        <li class="next" 
            :class="{ 'disabled' : pagination.current_page === pagination.last_page }">
                <a @click.prevent="nextPage()">
                    Next <span aria-hidden="true">&rarr;</span>
                </a>
        </li>
    </ul>
</nav>
</template>
<script>
export default {
    name: 'pager',

    props: {
        pagination: {
            type: Object,
            required: true
        },
        callback: {
            type: Function,
            required: true
        },
        showPageCount: {
            type: Boolean,
            default: true
        }
    },

    methods: {
        nextPage() {
            this.callback(this.pagination.current_page + 1);

            // this.$root.$emit('page:change', this.pagination.current_page + 1);
        },
        previousPage() {
            this.callback(this.pagination.current_page - 1);

            // this.$root.$emit('page:change', this.pagination.current_page - 1);
        }
    }
}
</script>
