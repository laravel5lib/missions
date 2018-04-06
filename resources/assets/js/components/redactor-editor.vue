<template>
	<textarea class="form-control" :value="value"></textarea>
</template>
<script type="text/javascript">
	import _ from 'underscore';
  export default {
    name: 'redactor-editor',
    props: {
      value: { type: String },
      placeholder: { type: String, default: 'Share your story...' },
      buttons: { type: Array, default() { return ['format', 'bold', 'italic', 'lists', 'image']; } },
      uuid: {type: String, required: true}
    },
    data() {
      return {
        inputText: null,
        removedFiles: []
      }
    },
    watch: {
      value(val) {
        console.log(val);
        // this.$emit('input', val);
      }
    },
    methods: {
      initEditor() {
        var vm = this;
        $(this.$el).redactor({
          plugins: ['video'],
          imageUpload: `/api/fundraisers/${this.uuid}/media`,
          imageEditable: false,
          minHeight: 300,
          buttons: this.buttons,
          placeholder: this.placeholder,
          callbacks: {
            change: this.updateValue
          }
        })
      },
      updateValue: _.debounce(function(val) {
        let changes = $(this.$el).redactor('storage.changes');
        for (let key in changes) {
          // if (changes[key].status === false)
          // {
            this.syncChanges(changes[key]);
            // console.log(changes[key]);
          // }
        }

        this.$emit('input', val);
      }, 200),
      syncChanges(item) {
        if(item.status === false) {
          this.removedFiles.push(item);
          this.removedFiles = _.uniq(this.removedFiles);
          return;
        }

        this.removedFiles = _.reject(this.removedFiles, item);
        this.removedFiles = _.uniq(this.removedFiles);
      }
    },
    mounted() {
      // load redactor
      // this way we don't need to bundle it with webpack
      $.getScript('/vendor/redactor/redactor.min.js')
        .done(() => {
          $.when(
            // can load multiple plugins here
            $.getScript('/vendor/redactor/plugins/video.js'),
            // $.getScript('/vendor/redactor/plugins/plugin.js'),
          ).done(() => {
              this.initEditor();
            })
        });

      let that = this;
      this.$parent.$on('delete:removedFiles', function() {
        this.$http.post('fundraisers/media/delete', { urls: _.map(that.removedFiles, 'url')}).then((response) => {
            
            console.log('all files deleted');

          }, this.$root.handleApiError);
      });

    }
  }
</script>