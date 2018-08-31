<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>{{ type | dashToSpace | titleCase }}</h5>
        </div>
        <div class="table-responsive" v-if="documents.length">
            <table class="table table-striped">
                <thead>
                    <tr class="active">
                        <th v-for="(value, key) in headings" v-if="keyIsAllowed(key)">
                            {{ key | underscoreToSpace | titleCase }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="document in documents" :class="{ 'text-danger': document.expired == 'Yes' }">
                        <td v-for="(value, key) in document" v-if="keyIsAllowed(key)">
                            <a :href="`/${firstUrlSegment}/records/${slug}/${document.id}`" v-if="key == 'name'">
                                <strong>{{ value }}</strong>
                            </a>
                            <span v-else>{{ value }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-body" v-if="!documents.length">
            <p class="lead text-center text-muted">No {{ type | underscoreToSpace }} found</p>
            <hr class="divider">
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination" :callback="changePage"></pager>
        </div>
    </div>
</template>

<script>
import DocumentTransformer from '../utilities/DocumentTransformer';

export default {

  name: 'TravelDocumentsList',

  props: {
    url: {
        type: String,
        required: true
    },
    type: {
        type: String,
        required: true
    }
  },

  data () {
    return {
        documents: [],
        pagination: {
            total: 0,
            per_page: 25
        }
    }
  },

  computed: {
        slug() {
            return this.type.replace(/_/g, '-');
        },
        docType() {
            // make it singular
            return this.type.substring(0, this.type.length - 1);
        },
        headings() {
            // grab the first document so we can use it's keys as table headings
            return _.first(this.documents);
        }
    },

    methods: {
        isArray(value) {
            return _.isArray(value);
        },
        fetchDocuments(params = {}) {
            params = {per_page: this.pagination.per_page};

            // replace underscores with dashes
            let type = this.type.replace(/_/g, '-');

            if (type == 'medical-releases') {
                params = {include: 'conditions,allergies'};
            }

            this.$http
                .get(`${this.url}`, {params})
                .then((response) => {
                    this.documents = this.mapRows(response.data.data);
                    this.pagination = response.data.meta;
                })
                .catch((error) => {
                    this.handleError(error)
                });
        },
        changePage(page) {
            let params = {page: page, per_page: this.pagination.per_page};
            this.fetchDocuments(params);
        },
        mapRows(documents) {
            let type = this.type.replace(/-/g, '_');
            return _.map(documents, function (document) {
                return new DocumentTransformer(document, type).get();
            })
        },
        handleError(error) {
            swal('Oops!', error, 'error');
        },
        keyIsAllowed(key) {
            return _.contains(['name', 'number', 'country', 'expired', 'last_updated', 'sent', 'replied', 'has_conditions', 'has_allergies'], key)
        }
    },

    created() {
        this.fetchDocuments();
    }
}
</script>

<style lang="css" scoped>
    tr:hover {
        background-color: #fcf8e3 !important;
    }
    th, td {
        white-space: nowrap;
    }
</style>