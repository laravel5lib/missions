<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"><label>Prospects</label></slot>
            <v-select @keydown.enter.prevent="null"  
                      multiple 
                      class="form-control" 
                      v-model="prospectsObj"
                      :options="prospectsList"
                      label="name" 
                      :name="name"
                      :id="name">
            </v-select>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
        </div>
    </div>
</template>
<script>
import _ from 'underscore';
import vSelect from "vue-select";

export default {
    name: 'select-prospects',

    components: {vSelect},

    data() {
        return {
            prospectsObj: null,
            prospectsList: [
                {value: "adults", name: "Adults"},
                {value: "young adults (18-29)", name: "Young Adults (18-29)"},
                {value: "teens (13+)", name: "Teens (13+)"},
                {value: "families", name: "Families"},
                {value: "men", name: "Men"},
                {value: "women", name: "Women"},
                {value: "media professionals", name: "Media Professionals"},
                {value: "pastors", name: "Pastors"},
                {value: "business leaders", name: "Business Leaders"},
                {value: "medical professionals", name: "Medical Professionals"},
                {value: "physicians", name: "Physicians"},
                {value: "surgeons", name: "Surgeons"},
                {value: "registered nurses", name: "Registered Nurses"},
                {value: "dentists", name: "Dentists"},
                {value: "hygienists", name: "Hygienists"},
                {value: "dental assistants", name: "Dental Assistants"},
                {value: "physician assistants", name: "Physician Assistants"},
                {value: "nurse practitioners", name: "Nurse Practitioners"},
                {value: "pharmacists", name: "Pharmacists"},
                {value: "physical therapists", name: "Physical Therapists"},
                {value: "chiropractors", name: "Chiropractors"},
                {value: "medical students", name: "Medical Students"},
                {value: "dental students", name: "Dental Students"},
                {value: "nursing students", name: "Nursing Students"}
            ],
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Array,
            default: null
        }
    },

    computed: {
        prospects: {
            get() {
                return _.pluck(this.prospectsObj, 'value') || [];
            },
            set() {}
        },
    },

    watch: {
        'prospects'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        if (this.value) {
            this.prospectsObj = _.filter(this.prospectsList, (p) => {
                return _.some(this.value, (prospect) => {
                    return prospect.toLowerCase() === p.value.toLowerCase();
                });
            });
        }

        this.$root.$on('form:reset', () => {
            this.prospectsObj = null;
        });
    }
}
</script>
