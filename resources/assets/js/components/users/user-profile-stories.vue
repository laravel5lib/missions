<template>
    <div>
        <button class="btn btn-primary btn-sm" @click="newMode=!newMode">Post Story <i class="fa fa-plus"></i></button>
        <hr>
        <div class="panel panel-default" v-if="newMode">
            <div class="panel-heading">
                <h5>Write New Story</h5>
            </div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="newStoryTitle">Title</label>
                        <input type="text" class="form-control" id="newStoryTitle" v-model="selectedStory.title">
                    </div>
                    <div class="form-group">
                        <label for="newStoryContent">Content
                            <button class="btn btn-info btn-xs" type="button" data-toggle="collapse" data-target="#markdownPrev" aria-expanded="false" aria-controls="markdownPrev">
                                Preview
                            </button>
                        </label>
                        <textarea class="form-control" id="newStoryContent" v-model="selectedStory.content" minlength="1" rows="20"></textarea>
                        <div class="collapse" id="markdownPrev">
                            <br>
                            <div class="well" v-html="selectedStory.content | marked"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-xs btn-default" type="button" @click="newMode = false">Cancel</button>
                        <button class="btn btn-xs btn-success" type="button" @click="createStory(selectedStory)">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-default" v-for="story in stories">
            <div class="panel-heading" role="tab" id="heading-{{ story.id }}">
                <h5>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ story.id }}" aria-expanded="true" aria-controls="collapseOne">
                        {{ story.title }}
                    </a>
                </h5>
            </div>
            <div id="collapse-{{ story.id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ story.id }}">
                <div class="panel-body" v-if="editMode !== story.id">
                    <h5 class="media-heading"><a href="#">{{ story.author }}</a> <small>published a story {{ story.updated_at|moment 'll' }}.</small></h5>
                    <div v-html="story.content | marked"></div>
                </div>
                <div class="panel-body" v-if="editMode === story.id">
                    <form>
                        <div class="form-group">
                            <label for="selectedStoryTitle">Title</label>
                            <input type="text" class="form-control" id="selectedStoryTitle" v-model="selectedStory.title">
                        </div>
                        <div class="form-group">
                            <label for="selectedStoryContent">Content
                                <button class="btn btn-info btn-xs" type="button" data-toggle="collapse" data-target="#markdownPrev{{$index}}" aria-expanded="false" aria-controls="markdownPrev">
                                    Preview
                                </button>
                            </label>
                            <textarea class="form-control" id="selectedStoryContent" v-model="selectedStory.content" minlength="1" rows="20"></textarea>
                            <div class="collapse" id="markdownPrev{{$index}}">
                                <br>
                                <div class="well" v-html="selectedStory.content | marked"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-xs btn-default" type="button" @click="editMode = null">Cancel</button>
                            <button class="btn btn-xs btn-success" type="button" @click="updateStory(selectedStory)">Update</button>
                        </div>
                    </form>
                </div>
                <div class="panel-footer" style="padding: 0;" v-if="isUser()">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-info" @click="selectedStory = story,editMode = story.id"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" @click="selectedStory = story,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <nav>
                <ul class="pagination pagination-sm">
                    <li :class="{ 'disabled': pagination.current_page == 1 }">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <modal v-if="isUser()" :show.sync="deleteModal" title="Remove Passport" small="true">
            <div slot="modal-body" class="modal-body">Are you sure you want to delete this Story?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeStory(selectedStory)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    var marked = require('marked');
    export default{
        name: 'user-profile-stories',
        components: {'modal': VueStrap.modal},
        props:['id', 'authId'],
        data(){
            return{
                stories: [],
                deleteModal: false,
                selectedStory: {
                    title: '',
                    content:''
                },
                editMode: false,
                newMode: false,
                // pagination vars
                page: 1,
                per_page: 5,
                pagination: {},

            }
        },
        watch: {
            'page': function (val, oldVal) {
                this.searchStories();
            },
        },
        filters: {
            marked: marked,
        },
        methods:{
            isUser(){
                return this.id === this.authId;
            },
            removeStory(story){
                if(story) {
                    this.$http.delete('stories/' + story.id).then(function (response) {
                        this.stories = _.reject(this.stories, function (item) {
                            return item.id === story.id;
                        });
                        this.resetData();
                        this.searchStories();
                    });
                }
            },
            updateStory(story){
                if(story) {
                    story.author_id = this.authId;
                    story.author_type = 'users';
                    this.$http.put('stories/' + story.id, story).then(function (response) {
                        this.editMode = false;
                        this.resetData();
                        return response.data.data;
                        //this.searchStories();
                    });
                }
            },
            createStory(story){
                if(story) {
                    story.author_id = this.authId;
                    story.author_type = 'users';

                    this.$http.post('stories', story).then(function (response) {
                        this.newMode = false;
                        this.resetData();
                        this.searchStories();
                    }, function (response) {
                        debugger;
                    });
                }
            },
            searchStories(){
                this.$http.get('stories?user=' + this.id, {
                    page: this.page,
                    per_page: this.per_page,
                }).then(function(response) {
                    this.stories = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            resetData(){
                this.selectedStory =  {
                    title: '',
                    content:''
                };
            }
        },
        ready(){
            this.searchStories();
        }
    }
</script>
