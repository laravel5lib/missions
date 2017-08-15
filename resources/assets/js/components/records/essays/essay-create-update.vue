<template>
    <div>
        <form id="CreateUpdateEssay" class="form-horizontal" novalidate>
            <spinner ref="spinner" size="sm" text="Loading"></spinner>

            <template v-if="forAdmin">
                <div class="col-sm-12">
                    <div class="form-group" v-error-handler="{ value: user_id, client: 'manager', server: 'user_id' }">
                        <label for="infoManager">Record Manager</label>
                        <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" :value="userObj" :options="usersArr" :on-search="getUsers" label="name"></v-select>
                        <select hidden name="manager" id="infoManager" class="hidden" v-model="user_id" v-validate="'required'">
                            <option :value="user.id" v-for="user in usersArr">{{user.name}}</option>
                        </select>
                    </div>
                </div>
            </template>

            <div class="row" v-error-handler="{ value: author, handle: 'author' }">
                <div class="col-sm-12">
                    <label for="author" class="control-label">Author Name</label>
                    <input type="text" class="form-control" name="author" id="author" v-model="author_name"
                           placeholder="Author Name" v-validate="'required|min:1|max:100'"
                           maxlength="150" minlength="1" required>
                </div>
            </div>

            <div class="row" v-for="(QA, indexQA) in content" v-error-handler="{ value: QA.a, client: 'question' + indexQA, messages: { req: 'Please provide an answer.'} }">
                <div class="col-sm-12">
                    <label class="control-label" v-text="QA.q"></label>
                    <textarea class="form-control" v-model="QA.a" rows="10" :field="'question' + indexQA" v-validate="'required'"></textarea>
                    <div class="errors-block"></div>
                </div>
            </div>
            <hr class="divider inv">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a v-if="!isUpdate" :href="'/'+ firstUrlSegment +'/records/essays'" class="btn btn-default">Cancel</a>
                    <a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
                    <a v-if="isUpdate" @click="back()" class="btn btn-default">Cancel</a>
                    <a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
                </div>
            </div>
        </form>
        <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
            <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import errorHandler from'../../error-handler.mixin';
    export default{
        name: 'essay-create-update',
        mixins: [errorHandler],
        components: {vSelect},
        props: {
            isUpdate: {
                type: Boolean,
                default: false
            },
            id: {
                type: String,
                default: null
            },
            forAdmin: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'CreateUpdateEssay',

                author_name: '',
                subject: 'Testimony',
                content: [
                    { q: 'Describe how you decided to follow Christ', a: ''},
                    { q: 'Describe your church involvement', a: ''},
                    { q: 'Describe your current walk with God', a: ''},
                    { q: 'Please describe any prior missions trip experience you have', a: ''},
                ],
                usersArr: [],
                userObj: null,

                // logic vars
                resource: this.$resource('essays{/id}', {include: 'user'}),
                hasChanged: false,
            }
        },
        computed: {
            user_id(){
                return  _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
            }
        },
        methods: {
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('users', { params: { search: search} }).then((response) => {
                    this.usersArr = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/'+ this.firstUrlSegment + '/records/essays/' + this.id;
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.resetErrors();
                if (this.$CreateUpdateEssay.valid) {
                    // this.$refs.spinner.show();
                    this.resource.post({
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Essay created.');
                        let that = this;
                        setTimeout(() =>  {
                            window.location.href = '/'+ that.firstUrlSegment +'/records/essays/' + resp.data.data.id;
                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create essay.')
                    });
                } else {
                    this.showError = true;
                }
            },
            update(){
                if ( _.isFunction(this.$validate) )
                    this.$validate(true);

                this.resetErrors();
                if (this.$CreateUpdateEssay.valid) {
                    // this.$refs.spinner.show();
                    this.resource.update({id: this.id}, {
                        author_name: this.author_name,
                        subject: this.subject,
                        content: this.content,
                        user_id: this.user_id,
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {
                            window.location.href = '/'+ that.firstUrlSegment + '/records/essays/' + that.id; 
                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                }
            },
        },
        mounted(){
            if (this.isUpdate) {
                this.resource.get({id: this.id}).then((response) => {

                    let essay = response.data.data;
                    this.author_name = essay.author_name;
                    this.subject = essay.subject;
                    this.content = essay.content;
                    this.userObj = essay.user.data;
                    this.usersArr.push(this.userObj);
                });
            }
        }
    }
</script>