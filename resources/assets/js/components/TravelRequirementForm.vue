<template>
    <ajax-form :horizontal="true" 
               :action="url" 
               :method="method" 
               :initial="initialData"
    >
        <template slot-scope="{ form }">

            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Details</h5>
                        <p class="text-muted">Provide a unique name and short description that the end-user will see.</p>
                        <p class="text-muted">The <strong>document type</strong> determines the kind of document expected and online form to use.</p>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <input-text name="name" :horizontal="true" classes="col-md-8" v-model="form.name">
                                    <label slot="label" class="control-label col-md-4">Name</label>
                                </input-text>

                                <input-textarea name="short_desc" :horizontal="true" classes="col-md-8" v-model="form.short_desc">
                                    <label slot="label" class="control-label col-md-4">Short Description</label>
                                </input-textarea>

                                <input-select name="document_type" 
                                              :horizontal="true" 
                                              classes="col-md-8" 
                                              v-model="form.document_type" 
                                              :options="docTypes"
                                >
                                    <label slot="label" class="control-label col-md-4">Document Type</label>
                                </input-select>

                                <input-checkbox name="upfront" v-model="form.upfront" :horizontal="true" classes="col-md-8">
                                    <label slot="label" class="control-label col-md-4">Due Upfront</label>
                                </input-checkbox>
                                
                                <input-date name="due_at" :horizontal="true" classes="col-md-8" v-model="form.due_at" v-if="!form.upfront">
                                    <label slot="label" class="control-label col-md-4">Due Date &amp; Time</label>
                                </input-date>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Rules (optional)</h5>
                        <p class="text-muted">You can define a set of rules that determine when and why this requirement should be applied to a reservation.</p>
                        <p class="text-muted">If <strong>no rules</strong> are set, the requirement will always be applied to every reservation.</p>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <select-roles name="roles" 
                                              :horizontal="true" 
                                              classes="col-md-8" 
                                              v-model="form.rules.roles" 
                                >
                                    <label slot="label" class="control-label col-md-4">Apply only to these roles:</label>
                                </select-roles>

                                <input-text name="age" :horizontal="true" classes="col-md-2" v-model="form.rules.age">
                                    <label slot="label" class="control-label col-md-4">Apply if under the age of:</label>
                                </input-text>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <a :href="`/admin${url}`" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ submitButtonLabel }}</button>
                    </div>
                </div>
            </div>

        </template>
    </ajax-form>
</template>

<script>
import documentTypes from '../data/document_types.json';
export default {

    props: {
        requesterId: {
            type: String,
            required: true
        },
        requesterType: {
            type: String,
            required: true
        },
        requirement: Object
    },

    data () {
        return {
            docTypes: documentTypes
        }
    },

    computed: {
        method() {
            if (this.requirement) return 'put';

            return 'post';
        },
        url() {
            let baseUrl = `/${ this.requesterType }/${ this.requesterId }/requirements`;

            if (this.requirement) {
                return baseUrl + `/${ this.requirement.id }`;
            }

            return baseUrl;
        },
        initialData() {
            return {
                name: this.requirement ? this.requirement.name : null, 
                short_desc: this.requirement ? this.requirement.short_desc : null, 
                document_type: this.requirement ? this.requirement.document_type : null, 
                upfront: this.requirement ? this.requirement.upfront : false,
                due_at: this.requirement ? this.requirement.due_at : null, 
                requester_type: this.requesterType, 
                requester_id: this.requesterId,
                rules: this.requirement ? (this.requirement.rules ? this.requirement.rules : false) : {age: null, roles: null}
            }
        },
        submitButtonLabel() {
            if (this.requirement) return 'Save Changes';

            return 'Add Requirement';
        }
    }
}
</script>