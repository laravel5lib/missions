/**
 * Created by jerezb on 2017-07-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from "p-immediate";
import test from "ava";

//load the component with a vue instance
RootInstance.template = '<div><admin-uploads-list v-ref:test-component></admin-uploads-list></div>';
RootInstance.components = {'admin-uploads-list': require('../../../../components/uploads/admin-uploads-list.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let AdminUploadsList = vm.$refs.testComponent;

test('uploads populated', t => {
    t.notDeepEqual(AdminUploadsList.uploads, []);
});

test('checkSource link', t => {
    t.regex(AdminUploadsList.checkSource(AdminUploadsList.uploads[0].source), /(w=100&q=25)/g)
});