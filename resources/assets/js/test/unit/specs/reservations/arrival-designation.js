/**
 * Created by jerezb on 2017-05-03.
 */
import {Vue, RootInstance} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><arrival-designation v-ref:test-component reservation-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></arrival-designation></div>';
RootInstance.components = {'arrival-designation': require('../../../../components/reservations/arrival-designation.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let arrivalDesignation = vm.$refs.testComponent;

test('default config should be ["eastern"]', (t) => {
    t.is(arrivalDesignation.designation.content[0], 'eastern');
});