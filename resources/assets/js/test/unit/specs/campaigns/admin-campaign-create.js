/**
 * Created by jerezb on 2017-05-08.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
// import Slim from '../../../../../../../public/js/slim';
// window.Slim = Slim;
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><admin-campaign-create v-ref:test-component ></admin-campaign-create></div>';
RootInstance.components = {'admin-campaign-create': require('../../../../components/campaigns/admin-campaign-create.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let adminCampaignCreate = vm.$refs.testComponent;

test('ready state', async (t) => {
    await nextTick();
    t.true(adminCampaignCreate.countries.length > 0);
});