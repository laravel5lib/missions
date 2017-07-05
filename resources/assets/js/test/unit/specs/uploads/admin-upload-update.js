/**
 * Created by jerezb on 2017-07-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from "p-immediate";
import test from "ava";

//load the component with a vue instance
RootInstance.template = '<div><upload-create-update is-update upload-id="90fd1c9a-78f1-4d2a-907d-31dbda79211e" type="avatar" name="avatar" :ui-selector="2" is-child v-ref:test-component></upload-create-update></div>';
RootInstance.components = {'upload-create-update': require('../../../../components/uploads/admin-upload-create-update.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let AdminUploader = vm.$refs.testComponent;

test('uploads populated', t => {
    t.notDeepEqual(AdminUploader.uploads, []);
});

test.before('set form data', t => {
    AdminUploader.name = 'Upload Test';
    AdminUploader.type = 'avatar';
    AdminUploader.tags = ['User'];
});

test.todo('update upload - currently an issue with slimAPI code causing - Error: Maximum call stack size exceeded');
/*
test('update upload', async t => {
    await nextTick();
    let promise = AdminUploader.update();
    if (!promise)  // if promise is a false boolean
        t.fail();
    promise.then(data => {
        i.is(data.id, '90fd1c9a-78f1-4d2a-907d-31dbda79211e');
        t.is(data.name, 'Upload Test');
        t.is(data.type, 'avatar');
        t.is(data.tags[0], 'User');
    })
});*/
