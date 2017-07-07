/**
 * Created by jerezb on 2017-07-06.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><transports-details v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" transport-id="b8d20208-8f44-470f-a05c-ef26fd299a2f"></transports-details></div>';
RootInstance.components = {'transports-details': require('../../../../components/admin/transports-details.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let AdminTransportsDetails = vm.$refs.testComponent;

test('transport populated', t => {
    t.not(AdminTransportsDetails.transport.id, null);
});

// Must test children individually
// Nothing else to test here