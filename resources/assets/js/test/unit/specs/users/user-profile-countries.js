/**
 * Created by jerezb on 2017-05-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><user-profile-countries v-ref:test-component id="112d15e5-c447-4c9e-bf25-b4cdb450c6a2"></user-profile-countries></div>';
RootInstance.components = {'user-profile-countries': require('../../../../components/users/user-profile-countries.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let userProfileCountries = vm.$refs.testComponent;

test('countries visited accolades object check', (t) => {
    t.is(userProfileCountries.accolades.name, 'countries_visited');
    t.not(userProfileCountries.accolades.items, null);
});