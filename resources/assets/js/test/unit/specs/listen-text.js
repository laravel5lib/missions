/**
 * Created by jerezb on 2017-02-23.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(_.extend(RootInstance, {
    template: '<div><listen-text v-ref:test-component :text="text" :event="event" :icon="icon" :size="size"></listen-text></div>',
    components: {
        'listen-text': require('../../../components/listen-text.vue')
    },
    data: {
        text: '',
        event: null,
        icon: null,
        size: '',
        count: 0
    },
    methods: {
        action(){
            return this.count = 1;
        }
    },
    ready() {
        this.$on('action', function () {
            this.action();
        })
    }
})).$mount('app');
let trigger = vm.$refs.testComponent;

test.beforeEach('set variables', (t) => {
    vm.text = 'Open';
    vm.event = 'action';
    vm.icon = 'fa fa-star';
    vm.size = 'btn-sm';
});

test('should have correct button text', async (t) => {
    await nextTick();
    t.is(trigger.text, vm.text);
});

test('should have correct event', async (t) => {
    await nextTick();
    t.true(_.isString(vm.event));
    t.true(_.isString(trigger.event));
    t.is(vm.event, trigger.event);
});

test('should have correct button icon class', async (t) => {
    await nextTick();
    t.is(trigger.icon, vm.icon);
});

test('should have correct button class', async (t) => {
    await nextTick();
    t.is(trigger.size, vm.size);
});