<template>
	<div>
		<template v-if="previewMode">
			<anchored-heading :level="level">{{ content }}</anchored-heading>
		</template>
		<template v-else>
			<div class="row bg-gray">
				<div class="col-xs-8">
                    <label>Heading</label>
                    <span class="help-block">Grab readers' attention with a heading.</span>
                </div>
				<div class="col-xs-4 text-right">
					<div class="btn-group">
						<button type="button" class="btn btn-xs btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
							<!-- <i class="fa fa-cog"></i> -->
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a v-if="!first" @click="moveUp">Move Up</a></li>
							<li v-if="!last"><a @click="moveDown">Move Down</a></li>
							<li role="separator" class="divider"></li>
							<li><a @click="remove">Remove Heading</a></li>
						</ul>
					</div>
				</div>
            </div>
            <div class="row bg-hollow">
                <div class="col-xs-12">
				    <input type="text" v-model="localContent" class="form-control input-lg" placeholder="Heading" aria-describedby="heading-text">
                </div>
			</div>
		</template>

	</div>
</template>
<style scoped>
	.no-margin {
		margin: 0;
	}
</style>
<script type="text/javascript">
    export default {
      name: 'header',
	    props: {
          id: { type: String, default: _.uniqueId('header_') },
            previewMode: {
                type: Boolean,
	            required: true
            },
            content: {
                type: String,
                required: true
            },
            level: {
                type: Number,
                required: false,
	            default: 2
            },
            drag: {
                type: Boolean,
	            required: false
            },
            last: {
                type: Boolean,
	            required: false
            },
            first: {
                type: Boolean,
	            required: false
            },
	    },
        data() {
            return {
                localContent: null,
	            // level: 2
            }
        },
	    watch: {
            localContent(val, oldVal) {
                this.$emit('update:content', val)
            }
	    },
        methods: {
            moveUp(){
                this.$emit('move-up')
            },
            moveDown(){
                this.$emit('move-down');
            },
            remove(){
                this.$emit('remove');
            },
            selectLevel(level){
                return this.level = level;
            },
        },
        mounted() {
            this.localContent = this.content;
        }
    }
</script>