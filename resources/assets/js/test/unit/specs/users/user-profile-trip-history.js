/**
 * Created by jerezb on 2017-05-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><user-profile-trip-history v-ref:test-component id="112d15e5-c447-4c9e-bf25-b4cdb450c6a2"></user-profile-trip-history></div>';
RootInstance.components = {'user-profile-trip-history': require('../../../../components/users/user-profile-trip-history.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let userProfileTripHistory = vm.$refs.testComponent;

test('trip history accolades object check', (t) => {
    t.true(userProfileTripHistory.accolades.name === 'trip_history' && _.isArray(userProfileTripHistory.accolades.items));
});