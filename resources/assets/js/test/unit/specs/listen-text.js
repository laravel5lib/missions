/**
 * Created by jerezb on 2017-02-23.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><listen-text v-ref:test-component text="One" event="listen"></listen-text></div>';
RootInstance.components = {'listen-text': require('../../../components/listen-text.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let ListenText = vm.$refs.testComponent;

test('should have correct text', (t) => {
    t.is(ListenText.text, 'One');
});

test('should have correct event', (t) => {
    t.true(_.isString(ListenText.event));
    t.is(ListenText.event, 'listen');
});

test('firing event changes text', async (t) => {
    vm.$emit('listen', 'Two');
    await nextTick();
    t.is(ListenText.text, 'Two');
});