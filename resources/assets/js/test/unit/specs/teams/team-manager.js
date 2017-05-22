/**
 * Created by jerezb on 2017-04-25.
 */
import { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage } from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';
// import login from '../../../components/login.vue';

//load the component with a vue instance
RootInstance.template = '<div><team-manager v-ref:test-component ></team-manager></div>';
RootInstance.components = { 'teamManager': require('../../../../components/teams/team-manager.vue') };
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let TeamManagerComponent = vm.$refs.testComponent;

test.before('handle ready', async (t) => {
 TeamManagerComponent.isAdminRoute = true;
    await nextTick();
});

test('get list of teams', (t) => {
 TeamManagerComponent.getTeams().then(result => {
        debugger;
    }, result => {
        debugger
    });
});


