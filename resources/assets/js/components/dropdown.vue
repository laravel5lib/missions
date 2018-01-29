<template>
	<div :is="isLi?'li':'div'" v-click-outside="blur"
	     :class="[{open:show,disabled:disabled,dropdown:isLi,'input-group-btn':inInput,'btn-group':!isLi&&!inInput}]"
	>
		<slot name="before"></slot>
		<template v-if="isLi">
			<a role="button" class="dropdown-toggle" :class="[buttonSize,{disabled:disabled}]"
			   @keyup.esc="show = false"
			   @click.prevent="toggle"
			>
				<slot name="button">{{ text }}</slot>
				<span class="caret"></span>
			</a>
		</template>
		<template v-else>
			<button type="button" :class="btnClasses || ['btn btn-' + type,buttonSize]" class="dropdown-toggle" :disabled="disabled"
			        @keyup.esc="show = false"
			        @click.prevent="toggle">
				<slot name="button"></slot>
				{{ text }}
				<span v-if="caret" class="caret"></span>
			</button>
		</template>
		<slot name="dropdown-menu">
			<ul class="dropdown-menu"><slot></slot></ul>
		</slot>
	</div>
</template>
<script>
    import $ from 'jquery'
//    import ClickOutside from 'vue-strap/src/directives/ClickOutside.js'
    let binded = [];

    function handler (e) { binded.forEach(el => { if (!el.node.contains(e.target)) el.callback(e) }) }

    function addListener (node, callback) {
      if (!binded.length) document.addEventListener('click', handler, false)
      binded.push({node, callback})
    }

    function removeListener (node, callback) {
      binded = binded.filter(el => el.node !== node ? true : !callback ? false : el.callback !== callback)
      if (!binded.length) document.removeEventListener('click', handler, false)
    }

    export default {
        directives: {
            ClickOutside: {
              bind (el, binding) {
                removeListener(el, binding.value)
                if (typeof binding.value !== 'function') {
                  if (process.env.NODE_ENV !== 'production') {
                    Vue.util.warn('ClickOutside only work with a function, received: v-' + binding.name + '="' + binding.expression + '"')
                  }
                } else {
                  addListener(el, binding.value)
                }
              },
              update (el, binding) {
                if (binding.value !== binding.oldValue){
                  removeListener(el, binding.oldValue)
                  addListener(el, binding.value)
                }
              },
              unbind (el, binding) {
                removeListener(el, binding.value)
              }
            }
        },
        props: {
            disabled: {type: Boolean, default: false},
            size: {type: String, default: null},
            text: {type: String, default: null},
            type: {type: String, default: 'default'},
            btnClasses: {type: String, default: ''},
            value: {type: Boolean, default: false},
            caret: {type: Boolean, default: false}
        },
        data () {
            var show = this.value
            return {show}
        },
        watch: {
            show (val) { this.$emit('input', val) },
            value (val) { this.show = val }
        },
        computed: {
            buttonSize () { return ~['lg', 'sm', 'xs'].indexOf(this.size) ? 'btn-' + this.size : '' },
            inInput () { return this.$parent._input },
            isLi () { return this.$parent._isTabs || this.$parent._navbar || this.$parent.menu },
            menu () { return !this.$parent || this.$parent.navbar },
            slots () { return this._slotContents },
            submenu () { return this.$parent && (this.$parent.menu || this.$parent.submenu) }
        },
        methods: {
            blur () { this.show = false },
            toggle () {
                if (!this.disabled) { this.show = !this.show }
            }
        },
        mounted () {
            $('ul', this.$el).on('click', 'li>a', e => { this.show = false })
        },
        beforeDestroy () {
            $('ul', this.$el).off()
        }
    }
</script>
