/**
 * Created by jerezb on 2017-07-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from "p-immediate";
import test from "ava";

//load the component with a vue instance
RootInstance.template = '<div><upload-create-update v-ref:test-component :ui-selector="1" is-child type="banner" ></upload-create-update></div>';
RootInstance.components = {'upload-create-update': require('../../../../components/uploads/admin-upload-create-update.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let AdminUploader = vm.$refs.testComponent;

test('uploads populated', t => {
    t.notDeepEqual(AdminUploader.uploads, []);
});

test.todo('select banner - currently an issue with slimAPI code causing - Error: Maximum call stack size exceeded');
/*
test('select banner', async t => {
    let selectedItem = AdminUploader.uploads[0];
    AdminUploader.selectExisting(selectedItem);
    await nextTick();
    t.deepEqual(AdminUploader.selectedBanner, selectedItem);
    t.is(AdminUploader.banner_upload_id, selectedItem.id);
});*/
