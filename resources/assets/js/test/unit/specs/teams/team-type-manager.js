/**
 * Created by jerezb on 2017-05-16.
 */
import { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage } from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';
// import login from '../../../components/login.vue';

//load the component with a vue instance
RootInstance.template = '<div><team-type-manager v-ref:test-component ></team-type-manager></div>';
RootInstance.components = { 'teamTypeManager': require('../../../../components/teams/team-type-manager.vue') };
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let TeamTypeManager = vm.$refs.testComponent;

test('teamTypes Array populated', t => {
    t.truthy(this.teamTypes.length);
});

test('edit form populates', t => {
    TeamTypeManager.editType(TeamTypeManager.teamTypes[0]);
    t.true(TeamTypeManager.editTypeMode );
    t.isNot(TeamTypeManager.currentType.name, '');
});

test('form is valid', t => {
    t.true(TeamTypeManager.$TypeForm.valid);
});