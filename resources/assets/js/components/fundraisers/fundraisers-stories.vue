<template>
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <template>
        <div class="panel panel-default panel-body">
            <div class="row">
                <div class="col-xs-8">
                    Share your stories with the world
                </div>
                <div class="col-xs-4 text-right">
                    <button class="btn btn-primary btn-xs" @click="newMode=!newMode"><i class="fa fa-plus icon-left"></i> Post Story</button>
                </div>
            </div>
        </div>
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
                            <button class="btn btn-default-hollow btn-xs" type="button" @click="newMarkedContentToggle = !newMarkedContentToggle">
                                <span v-show="!newMarkedContentToggle">Preview</span>
                                <span v-show="newMarkedContentToggle">Edit</span>
                            </button>
                            <button class="btn btn-default-hollow btn-xs" type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                Examples
                            </button>
                        </label>
                        <textarea v-show="!newMarkedContentToggle" class="form-control" id="newStoryContent" v-model="selectedStory.content" minlength="1" rows="10"></textarea>
                        <div class="collapse" :class="{ 'in': newMarkedContentToggle }">
                            <div class="well" v-html="marked(selectedStory.content)"></div>
                        </div>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" v-model="includeProfile"> Post to profile
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-default" type="button" @click="newMode = false,resetData()">Cancel</button>
                        <button class="btn btn-sm btn-primary" type="button" @click="createStory(selectedStory)">Publish</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" v-if="stories.length < 1">
            <div class="col-xs-12">
                <p class="lead text-muted text-center">No updates yet.</p>
            </div>
        </div>
        <div class="panel panel-default" v-for="(story, index) in stories">
            <div class="panel-heading" role="tab" :id="'heading-' + story.id">
                <h5>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#collapse-' + story.id" aria-expanded="true" aria-controls="collapseOne">
                        {{ story.title }}
                    <i class="fa fa-chevron-down pull-right"></i></a>
                </h5>
            </div>
            <div :id="'collapse-' + story.id" class="panel-collapse collapse" :class="{ 'in': index === 0}" role="tabpanel" :aria-labelledby="'heading-' + story.id">
                <div class="panel-body" v-if="editMode !== story.id">
                <div class="row">
                    <div class="col-sm-8">
                        <h5 class="media-heading" style="margin:4px 0 10px;"><a href="#">{{ story.author }}</a> <small>published a story {{ story.updated_at|moment('ll') }}.</small></h5>
                    </div>
                    <div class="col-sm-4 text-right hidden-xs">
                        <div style="padding: 0;">
                            <div role="group" aria-label="...">
                                <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,editMode = story.id"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,deleteModal = true"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center visible-xs">
                        <div style="padding: 0;">
                            <div role="group" aria-label="...">
                                <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,editMode = story.id"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="selectedStory = story,deleteModal = true"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <button class="btn btn-default-hollow btn-xs" type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                    Examples
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

        <modal :value="deleteModal" @closed="deleteModal=false" title="Remove Passport" :small="true">
            <div slot="modal-body" class="modal-body">Delete this Story?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeStory(selectedStory)'>Delete</button>
            </div>
        </modal>

    </div>
</template>
<script type="text/javascript">
    let marked = require('marked');
    export default{
        name: 'fundraisers-stories',
        props:['id', 'sponsorId'],
        data(){
            return{
                stories: [],
                deleteModal: false,
                selectedStory: {
                    title: '',
                    content:'',
                    publications: [
                        { type: 'fundraisers', id: this.id },
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
            isUser(){
                return this.$root.user && this.sponsorId === this.$root.user.id;
            },
        },
        methods:{
            marked: marked, 
            removeStory(story){
                if(story) {
                    this.$http.delete('stories/' + story.id).then((response) => {
                        this.stories = _.reject(this.stories, (item) => {
                            return item.id === story.id;
                        });
                        this.resetData();
                        this.searchStories();
                    });
                }
            },
            updateStory(story){
                if(story) {
                    story.author_id = this.$root.user.id;
                    story.author_type = 'users';
                    story.publications = [
                        { type: 'fundraisers', id: this.id },
                    ];

                    if (this.includeProfile) {
                        story.publications.push({ type: 'users', id: this.$root.user.id });
                    }

                    // this.$refs.spinner.show();
                    this.$http.put('stories/' + story.id, story).then((response) => {
                        this.editMode = false;
                        this.resetData();
                        //this.searchStories();
                        // this.$refs.spinner.hide();
                        return response.data.data;
                    }).catch(this.$root.handleApiError);
                }
            },
            createStory(story){
                if(story) {
                    story.author_id = this.$root.user.id;
                    story.author_type = 'users';

                    if (this.includeProfile) {
                        story.publications.push({ type: 'users', id: this.$root.user.id });
                    }

                    // this.$refs.spinner.show();
                    this.$http.post('stories', story).then((response) => {
                        this.newMode = false;
                        this.resetData();
                        this.searchStories();
                        // this.$refs.spinner.hide();
                    }, this.$root.handleApiError);
                }
            },
            searchStories(){
                // this.$refs.spinner.show();
                this.$http.get('stories', { params: {
                    fundraiser: this.id,
                    page: this.pagination.current_page,
                    per_page: this.per_page,
                }}).then((response) => {
                    this.stories = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                }, this.$root.handleApiError);
            },
            resetData(){
                this.selectedStory =  {
                    title: '',
                    content:'',
                    publications: [
                        { type: 'fundraisers', id: this.id },
                    ]
                };
                this.includeProfile = false;
            }
        },
        mounted(){
            this.searchStories();
        }
    }
</script>