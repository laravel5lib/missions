<template>
    <div>
        <div class="panel panel-default" v-for="story in stories">
            <div class="panel-heading" role="tab" id="heading-{{ story.id }}">
                <h5>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ story.id }}" aria-expanded="true" aria-controls="collapseOne">
                        {{ story.title }}
                    </a>
                </h5>
            </div>
            <div id="collapse-{{ story.id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-{{ story.id }}">
                <div class="panel-body">
                    <h5 class="media-heading"><a href="#">{{ story.author }}</a> <small>published a story {{ story.updated_at|moment 'll' }}.</small></h5>
                    {{ story.content }}
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
    </div>
</template>
<script>

    export default{
        name: 'group-profile-stories',
        props:['id'],
        data(){
            return{
                stories: [],

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
        methods:{
            searchStories(){
                this.$http.get('stories', {
                    group: this.id,
                    page: this.page,
                    per_page: this.per_page,
                }).then(function(response) {
                    this.stories = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready(){
            this.searchStories();
        }
    }
</script>
