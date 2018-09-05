<style lang="css" scoped>
</style>

<template>
    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>{{ docType | underscoreToSpace | dashToSpace | titleCase }}</h5>
                </div>
                <div class="col-xs-4 text-right" v-if="canEdit">
                    <a :href="`/${firstUrlSegment}/records/${slug}/${travelDocument.id}/edit`" class="btn btn-sm btn-primary">Edit</a>
                </div>
            </div>
        </div>

        <div class="list-group">

            <div class="list-group-item" v-for="(value, key) in travelDocument" v-if="key != 'id'">
                
                <div class="row">

                    <div class="col-xs-4 text-muted text-right">
                        {{ key | underscoreToSpace | titleCase }}
                    </div>

                    <div class="col-xs-8">

                        <ul v-if="isArray(value)">
                            <li v-for="item in value" v-html="item"></li>
                        </ul>
                        <span v-else v-html="value"></span>

                    </div>

                </div>

            </div>

        </div>

    </div>
</template>

<script>
import DocumentTransformer from '../utilities/DocumentTransformer';

export default {

    props: {
        id: {
            type: String,
            required: true
        },
        type: {
            type: String,
            required: true
        },
        canEdit: {
            type: Boolean,
            default: false
        }
    },
    
    data() {
        return {
            travelDocument: null
        }
    },

    computed: {
        slug() {
            return this.type.replace(/_/g, '-');
        },

        docType() {
            // make it singular
            return this.type.substring(0, this.type.length - 1);
        }
    },

    methods: {

        /**
         * Check if the given value is an array
         */
        isArray(value) {
            return _.isArray(value);
        },

        /**
         * Fetch the document from the API
         */
        fetchDocument() {
            let params = {};

            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            if (type == 'medical-releases') {
                params = {include: 'conditions,allergies'};
            }

            this.$http
                .get(`${this.type}/${this.id}`, {params})
                .then((response) => {
                    this.travelDocument = this.mapRows(response.data.data);
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },

        /**
         * Map document data
         */
        mapRows(document) {
            let type = this.type.replace(/-/g, '_');
            return new DocumentTransformer(document, type).get();
        },

        /**
         * Show an alert with error message
         */
        handleError(error) {
            swal('Oops!', error, 'error');
        },
    },

    mounted() {
        this.fetchDocument();
    }

}
</script>