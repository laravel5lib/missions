/**
 * Created by jerezb on 2017-06-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><rooming-type-manager v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" ></rooming-type-manager></div>';
RootInstance.components = {'rooming-type-manager': require('../../../../components/rooms/rooming-type-manager.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let RoomingTypeManager = vm.$refs.testComponent;

test('rooming types populated', t => {
    t.true(RoomingTypeManager.roomTypes.length > 0)
});

test('new type', t => {
    RoomingTypeManager.newTypeModel();
});