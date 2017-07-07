/**
 * Created by jerezb on 2017-07-06.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><transports-details-itinerary v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" :transport="$root.transport"></transports-details-itinerary></div>';
RootInstance.components = {'transports-details-itinerary': require('../../../../components/admin/transports-details-itinerary.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
RootInstance.data.transport = {
    "id": "b8d20208-8f44-470f-a05c-ef26fd299a2f",
    "campaign_id": "b304fd5a-3c18-4a77-9722-e6138f3429d7",
    "type": "flight",
    "vessel_no": "DL100",
    "name": "Delta Airlines",
    "domestic": true,
    "capacity": 9999,
    "call_sign": null,
    "created_at": "2017-04-21 18:14:34",
    "updated_at": "2017-04-21 18:14:34",
    "links": [
        {
            "rel": "self",
            "uri": "/api/transports/b8d20208-8f44-470f-a05c-ef26fd299a2f"
        }
    ]
};
const vm = new Vue(RootInstance).$mount('app');

let AdminTransportsDetailsItineary = vm.$refs.testComponent;

test('transport populated', t => {
    t.not(AdminTransportsDetailsItineary.transport, null);
});

test('activities populated', t => {
    t.not(AdminTransportsDetailsItineary.activities, []);
});