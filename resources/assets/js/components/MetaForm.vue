<template>
  <ajax-form :method="method" :action="action" :initial="initial" ref="ajax">
        <template slot-scope="{ form }">
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label>Meta</label>
                </div>
            </div>
        </div>
        <div class="row" v-for="(item, index) in form.meta" :key="index">
            <div class="col-md-4">
                <input-text :name="'item.'+index+'.label'" v-model="item.label">
                    <span class="help-block" slot="help-text">Label</span>
                </input-text>
            </div>
            <div class="col-md-6">
                <input-text :name="'item.'+index+'.value'" v-model="item.value">
                    <span class="help-block" slot="help-text">Value</span>
                </input-text>
            </div>
            <div class="col-md-2">
                <p class="form-control-static">
                    <a class="small" role="button" @click.prevent="removeItem(index)">
                        <i class="fa fa-times"></i>
                    </a>
                </p>
            </div>
        </div>
        <a role="button" class="small" @click.prevent="addItem"><i class="fa fa-plus-circle"></i> Add another row</a>
        <div class="row">
            <hr class="divider">
            <div class="col-sm-12 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        </template>
    </ajax-form>
</template>
<script>
export default {
    props: ['action', 'method', 'meta'],

    computed: {
        'initial'() {
            return {meta: (this.meta ? this.meta : { 0: {label: null, value: null}}) }
        }
    },

    methods: {
        addItem() {
            this.$refs.ajax.form.meta.push({
                label: null,
                value: null
            });
            this.$forceUpdate()
        },
        removeItem(index) {
            this.$refs.ajax.form.meta.splice(index, 1);
            this.$forceUpdate()
        }
    },
}
</script>

