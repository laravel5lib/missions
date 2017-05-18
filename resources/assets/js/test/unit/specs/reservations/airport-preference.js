/**
 * Created by jerezb on 2017-05-03.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><airport-preference v-ref:test-component reservation-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></airport-preference></div>';
RootInstance.components = {'airport-preference': require('../../../../components/reservations/airport-preference.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let airportPref = vm.$refs.testComponent;

test('get airports from Utilities API', (t) => {
   airportPref.getAirports('', false).then(result => {
       t.true(result.length > 1)
   });
});

test('get specific airport from Utilities API', (t) => {
    airportPref.getAirport('DTW', false).then(result => {
        t.true(result.iata, 'DTW');
    });
});