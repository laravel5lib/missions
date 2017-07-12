/**
 * Created by jerezb on 2017-07-06.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><transports v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28"></transports></div>';
RootInstance.components = {'transports': require('../../../../components/admin/transports.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let AdminTransports = vm.$refs.testComponent;

test('airlines populated', t => {
    t.not(AdminTransports.UTILITIES.airlines.length, 0);
});

test('transports populated', t => {
    t.not(AdminTransports.transports.length, 0);
});

test('toggle domestic/international tabs', t => {
    AdminTransports.changeList('international');
    t.is(AdminTransports.options.params.isDomestic, 'no');
});

test('transport modal - create', async t => {
    AdminTransports.openTransportModal();
    // await nextTick();
    t.true(AdminTransports.showTransportsModal);
    t.false(AdminTransports.transportsModalEdit);
    t.is(AdminTransports.selectedTransport.id, undefined);
    await nextTick();
    AdminTransports.handleTransport();
});

test('transport modal - edit', async t => {
    AdminTransports.openTransportModal(AdminTransports.transports[0]);
    // await nextTick();
    t.true(AdminTransports.showTransportsModal);
    t.true(AdminTransports.transportsModalEdit);
    t.not(AdminTransports.selectedTransport.id, undefined);
    await nextTick();
    AdminTransports.handleTransport();
});

test('delete transport', async t => {
    AdminTransports.openTransportDeleteModal(AdminTransports.transports[0]);
    await nextTick();
    t.true(AdminTransports.showTransportDeleteModal);
    AdminTransports.deleteTransport();
});