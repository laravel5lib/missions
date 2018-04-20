<template>
  <div>
    <spinner ref="loader" size="xl" :fixed="false" text="Loading..."></spinner>
	<template v-if="! items.length">
        <slot name="empty">
            No results
        </slot>
	</template>
    <template v-else>
        <div v-for="(item, index) in items" :key="index">
            <slot :item="item" name="item">
                <code>{{ item }}</code>
            </slot>
        </div>
    </template>
  </div>
</template>
<script>
export default {
    name: 'data-list',

    props: {
        url: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            items: [],
            pagination: {
                current_page: 1,
                last_page: 1,
                total: 0
            }
        }
    },

    watch: {
      filters: {
        deep: true,
        handler(val) {
            this.pagination.current_page = 1;
            this.getItems();
        }
      }
    },

    methods: {
        getItems(page = this.pagination.current_page) {

            this.$refs.loader.show();
            
            let params = _.extend({page}, this.filters);

            this.$http.get(this.url, {params}).then((response) => {
                this.items = response.data.data;
                this.pagination = response.data.meta;

                this.$refs.loader.hide();
            });
        }
    },

    mounted() {
        this.getItems();
        
        this.$nextTick(function () {
            this.$root.$on('form:success', this.getItems);
        }.bind(this));
    }
}
</script>
