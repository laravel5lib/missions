<template>
    <div>
        <spinner ref="spinner" global size="sm" text="Loading"></spinner>
        <template v-if="isManager">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h5>Share your stories with the world</h5>
                </div>
                <div class="col-sm-4 text-right">
                    <button class="btn btn-primary btn-sm" @click="newMode=!newMode">Post Story <i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-12 text-center">
                    <h5>Share your stories with the world</h5>
                </div>
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary btn-sm" @click="newMode=!newMode">Post Story <i class="fa fa-plus"></i></button>
                </div>
            </div>
            <hr class="divider inv">
        </template>
        <div class="panel panel-default" v-if="newMode">
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="newStoryTitle">Story Title</label>
                        <input type="text" class="form-control" id="newStoryTitle" v-model="selectedStory.title" maxlength="60">
                    </div>
                    <div class="form-group">
                        <label for="newStoryContent">Content
                            <button class="btn btn-default-hollow btn-sm" type="button" @click="newMarkedContentToggle = !newMarkedContentToggle">
                                <span v-show="!newMarkedContentToggle">Preview</span>
                                <span v-show="newMarkedContentToggle">Edit</span>
                            </button>
                        </label>
                        <textarea v-show="!newMarkedContentToggle" class="form-control" id="newStoryContent" v-model="selectedStory.content" minlength="1" rows="10"></textarea>
                        <div class="collapse" :class="{ 'in': newMarkedContentToggle }">
                            <div class="well" v-html="marked(selectedStory.content)"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-default" type="button" @click="newMode = false,resetData()">Cancel</button>
                        <button class="btn btn-sm btn-primary" type="button" @click="createStory(selectedStory)">Publish</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-default" v-for="(story, index) in stories">
            <div class="panel-heading" role="tab" :id="'heading-' + story.id">
                <h5>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#collapse-' + story.id " aria-expanded="true" aria-controls="collapseOne">
                        {{ story.title }}
                        <i class="fa fa-chevron-down pull-right"></i></a>
                </h5>
            </div>
            <div :id="'collapse-' + story.id" class="panel-collapse collapse" :class="{'in': index === 0 }" role="tabpanel" :aria-labelledby="'heading-' + story.id">
                <div class="panel-body" v-if="editMode !== story.id">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="media-heading" style="margin:4px 0 10px;"><a href="#">{{ story.author }}</a> <small>published a story {{ story.updated_at|moment('ll') }}.</small></h5>
                        </div>
                        <div class="col-sm-4 text-right hidden-xs">
                            <div style="padding: 0;" v-if="isManager">
                                <div role="group" aria-label="...">
                                    <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,editMode = story.id"><i class="fa fa-pencil"></i> Edit</a>
                                    <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,deleteModal = true"><i class="fa fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center visible-xs">
                            <div style="padding: 0;" v-if="isManager">
                                <div role="group" aria-label="...">
                                    <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,editMode = story.id"><i class="fa fa-pencil"></i> Edit</a>
                                    <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,deleteModal = true"><i class="fa fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv">
                    <p class="small" v-html="marked(story.content)"></p>
                </div>
                <div class="panel-body" v-if="editMode === story.id">
                    <form>
                        <div class="form-group">
                            <label for="selectedStoryTitle">Title</label>
                            <input type="text" class="form-control" id="selectedStoryTitle" v-model="selectedStory.title" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label for="selectedStoryContent">Content
                                <button class="btn btn-default-hollow btn-sm" type="button" data-toggle="collapse" :data-target="'#markdownPrev' + index"
                                        aria-expanded="false" aria-controls="markdownPrev" @click="editMarkedContentToggle = !editMarkedContentToggle">
                                    <span v-show="!editMarkedContentToggle">Preview</span>
                                    <span v-show="editMarkedContentToggle">Edit</span>
                                </button>
                            </label>
                            <textarea v-show="!editMarkedContentToggle" class="form-control" id="selectedStoryContent" v-model="selectedStory.content" minlength="1" rows="20"></textarea>
                            <div class="collapse" :class="{ 'in': editMarkedContentToggle }">
                                <div class="well" v-html="marked(selectedStory.content)"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-default" type="button" @click="editMode = null">Cancel</button>
                            <button class="btn btn-sm btn-primary" type="button" @click="updateStory(selectedStory)">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination="pagination" pagination-key="pagination" :callback="searchStories"></pagination>
        </div>

        <modal class="text-center" v-if="isManager" :value="deleteModal" @closed="deleteModal=false" title="Delete Story" :small="true">
            <div slot="modal-body" class="modal-body text-center">Delete this Story?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeStory(selectedStory)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name: 'group-profile-stories',
        props:['id', 'managerIds'],
        data(){
            return{
                stories: [],
                deleteModal: false,
                selectedStory: {
                    title: '',
                    content:'',
                    publications: [
                        { type: 'groups', id: this.id },
                    ]
                },
                editMode: false,
                editMarkedContentToggle: false,

                newMode: false,
                newMarkedContentToggle: false,
                includeProfile: false,

                // pagination vars
                per_page: 5,
                pagination: { current_page: 1 },

            }
        },
        computed: {
            isManager() {
                return _.contains(this.managerIds, this.$root.user.id);
            },
        },
        filters: {
            marked: marked,
        },
        methods:{
            removeStory(story){
                if(story) {
                    this.$http.delete('stories/' + story.id).then((response) => {
                        this.stories = _.reject(this.stories, (item) => {
                            return item.id === story.id;
                        });
                        this.resetData();
                        this.searchStories();
                    }).catch(this.$root.handleApiError);
                }
            },
            updateStory(story){
                if(story) {
                    story.author_id = this.$root.user.id;
                    story.author_type = 'users';
                    story.publications = [{ type: 'groups', id: this.id }];

                    // this.$refs.spinner.show();
                    this.$http.put('stories/' + story.id, story).then((response) => {
                        this.editMode = false;
                        this.resetData();
                        // this.$refs.spinner.hide();
                        return response.data.data;
                        //this.searchStories();
                    }).catch(this.$root.handleApiError);
                }
            },
            createStory(story){
                if(story) {
                    story.author_id = this.$root.user.id;
                    story.author_type = 'users';
                    story.publications = [{ type: 'groups', id: this.id }];

                    // this.$refs.spinner.show();
                    this.$http.post('stories', story).then((response) => {
                        this.newMode = false;
                        this.resetData();
                        this.searchStories();
                        // this.$refs.spinner.hide();
                    }).catch(this.$root.handleApiError);
                }
            },
            searchStories(){
                // this.$refs.spinner.show();
                this.$http.get('stories', { params: {
                    group: this.id,
                    page: this.pagination.current_page,
                    per_page: this.per_page,
                }}).then((response) => {
                    this.stories = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }).catch(this.$root.handleApiError);
            },
            resetData(){
                this.selectedStory =  {
                    title: '',
                    content:''
                };
            }
        },
        mounted(){
            this.searchStories();
        }
    }
</script>
