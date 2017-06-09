/**
 * Created by jerezb on 2017-06-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><rooming-wizard v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" user-id="112d15e5-c447-4c9e-bf25-b4cdb450c6a2" group-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></rooming-wizard></div>';
RootInstance.components = {'rooming-manager': require('../../../../components/rooms/rooming-wizard.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let RoomingWizard = vm.$refs.testComponent;

test('', t => {

});