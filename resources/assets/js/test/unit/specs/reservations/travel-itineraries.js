/**
 * Created by jerezb on 2017-05-01.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';
// import login from '../../../components/login.vue';

//load the component with a vue instance
RootInstance.template = '<div><travel-itineraries v-ref:test-component reservation-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></travel-itineraries></div>';
RootInstance.components = {'travel-itineraries': require('../../../../components/reservations/travel-itineraries.vue')};
const vm = new Vue(RootInstance).$mount();

let travelItineraries = vm.$refs.testComponent;
test.beforeEach('set document object', async (t) => {
    travelItineraries.document = { id: '8850333f-7cc2-4509-b6db-33c8d3e7642c' };
    await nextTick();
});

test('get activity types from Utilities API', (t) => {
    travelItineraries.getTypes().then(response => {
        t.true(response.length > 1)
    });
});

test('make a new Itinerary', async (t) => {
    await nextTick();
    travelItineraries.newItinerary('Itinerary');
    t.true(travelItineraries.itinerary.items.length === 2);
 });

test('get itinerary from API', async (t) => {
    await nextTick();
    travelItineraries.getItinerary().then(response => {
        t.true(response.id === travelItineraries.document.id)
    });
});

test('toggle connection', async (t) => {
    await nextTick();
    travelItineraries.toggleConnection();
    t.true(travelItineraries.itinerary.items.length === 3);
});

test('toggle connection again', async (t) => {
    await nextTick();
    travelItineraries.toggleConnection();
    t.true(travelItineraries.itinerary.items.length === 2);
});

test('toggle departure', async (t) => {
    await nextTick();
    let item = travelItineraries .itinerary.items[travelItineraries.itinerary.items.length-1];
    travelItineraries.toggleReturningOnOwn(item);
    t.falsy(item.transport && item.hub)
});

test('toggle departure again', async (t) => {
    await nextTick();
    let item = travelItineraries.itinerary.items[travelItineraries.itinerary.items.length-1];
    travelItineraries.toggleReturningOnOwn(item);
    t.truthy(item.transport && item.hub)
});

// Tests pass, but I cannot narrow down the cause of the error 'TypeError: Cannot read property 'read' of undefined'
// the error happens when the itinerary items are populated and the child components are loaded
