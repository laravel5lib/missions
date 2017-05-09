/**
 * Created by jerezb on 2017-05-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><admin-campaign-details v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" ></admin-campaign-details></div>';
RootInstance.components = {'admin-campaign-details': require('../../../../components/campaigns/admin-campaign-details.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let adminCampaignDetails = vm.$refs.testComponent;

test('campaign loaded', (t) => {
    t.truthy(adminCampaignDetails.campaign.hasOwnProperty('id'));
});