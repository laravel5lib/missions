/**
 * Created by jerezb on 2017-05-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
// import Slim from '../../../../../../../public/js/slim';
// window.Slim = Slim;
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><admin-campaign-edit v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" ></admin-campaign-edit></div>';
RootInstance.components = {'admin-campaign-edit': require('../../../../components/campaigns/admin-campaign-edit.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let adminCampaignEdit = vm.$refs.testComponent;

test('campaign loaded', (t) => {
    t.truthy(adminCampaignEdit.name !== null);
});

test('after loading data, fields are valid', (t) => {
    t.true(adminCampaignEdit.$UpdateCampaign.valid);
});

test('validate dates', (t) => {
    t.true(moment(adminCampaignEdit.started_at).isValid() && moment(adminCampaignEdit.ended_at).isValid() && moment(adminCampaignEdit.published_at).isValid())
});

test('validate country object', (t) => {
    t.true(adminCampaignEdit.countryCodeObj.hasOwnProperty('code') && adminCampaignEdit.countryCodeObj.hasOwnProperty('name') && adminCampaignEdit.country_code === adminCampaignEdit.countryCodeObj.code);
});