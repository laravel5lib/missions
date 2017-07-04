/**
 * Created by jerezb on 2017-06-14.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><regions-accommodations v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28"></regions-accommodations></div>';
RootInstance.components = {'regions-accommodations': require('../../../../components/regions/regions-accommodations.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let RegionsAccommodations = vm.$refs.testComponent;

test('regions populated', t => {
    t.truthy(RegionsAccommodations.regions.length);
});

test('room types populated', t => {
    t.truthy(RegionsAccommodations.roomTypes.length);
});

test('accommodations populated', async t => {
    RegionsAccommodations.currentRegion = RegionsAccommodations.regions[0];
    await nextTick();
    t.truthy(RegionsAccommodations.accommodations.length);
    await nextTick();
});

test.after('create accommodation', async t => {
    RegionsAccommodations.startNewAccommodation();
    await nextTick();
    t.true(RegionsAccommodations.showAccommodationManageModal);
    RegionsAccommodations.currentAccommodation = _.extend(RegionsAccommodations.currentAccommodation, {
        name: "4 Seasons Hotel"
    });
    await nextTick();
    t.truthy(RegionsAccommodations.manageAccommodation());
    // await nextTick();
    // t.falsy(RegionsAccommodations.currentAccommodation);
    // t.false(RegionsAccommodations.showAccommodationManageModal);
});

test.after('update accommodation', async t => {
    RegionsAccommodations.currentAccommodation = RegionsAccommodations.accommodations[0];
    await nextTick();
    RegionsAccommodations.editAccommodation(RegionsAccommodations.accommodations[1]);
    await nextTick();
    RegionsAccommodations.currentAccommodation = _.extend(RegionsAccommodations.currentAccommodation, {
        description: "Lorem Ipsum..."
    });
    await nextTick();
    t.truthy(RegionsAccommodations.manageAccommodation());
});

/*test.after('delete accommodation', async t => {
    RegionsAccommodations.currentAccommodation = RegionsAccommodations.accommodations[0];
    await nextTick();
    t.false(RegionsAccommodations.showAccommodationDeleteModal);
    RegionsAccommodations.openDeleteAccommodationModal(RegionsAccommodations.accommodations[0]);
    await nextTick();
    t.true(RegionsAccommodations.showAccommodationDeleteModal);
    RegionsAccommodations.deleteAccommodation();
    await nextTick();
    t.false(RegionsAccommodations.showAccommodationDeleteModal);
});*/
