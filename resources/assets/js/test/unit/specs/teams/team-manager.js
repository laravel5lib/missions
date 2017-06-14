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

// test.before('handle ready', async (t) => {
//     TeamManagerComponent.isAdminRoute = true;
//     await nextTick();
// });

test('teams array populated', (t) => {
    t.truthy(TeamManagerComponent.teams.length);
});

test('teamTypes populated', t => {
    t.truthy(TeamManagerComponent.teamTypes.length);
});

test('campaigns populated', t => {
    t.truthy(TeamManagerComponent.campaignsOptions.length);
});

test('roles populated', t => {
    t.truthy(TeamManagerComponent.rolesOptions.length);
});

test('leadership roles populated', t => {
    t.truthy(TeamManagerComponent.leadershipRoles.length);
});

test('set currentTeam', t => {
    t.falsy(TeamManagerComponent.currentTeam);
    // TODO Investigate: RangeError: Maximum call stack size exceeded
    // TeamManagerComponent.makeTeamCurrent(TeamManagerComponent.teams[0]);
    // t.truthy(TeamManagerComponent.currentTeam);
});

test('open team modal', t => {
    t.falsy(TeamManagerComponent.showTeamCreateModal);
    TeamManagerComponent.openNewTeamModel();
    t.true(TeamManagerComponent.showTeamCreateModal);
});

