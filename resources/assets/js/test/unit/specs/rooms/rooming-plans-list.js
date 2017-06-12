/**
 * Created by jerezb on 2017-06-09.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><rooming-plans-list v-ref:test-component campaign-id="5830c58b-a183-49ec-a61e-a3c748b33c28" user-id="112d15e5-c447-4c9e-bf25-b4cdb450c6a2" group-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></rooming-plans-list></div>';
RootInstance.components = {'rooming-plans-list': require('../../../../components/rooms/rooming-plans-list.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let RoomingPlansList = vm.$refs.testComponent;

test.before('open modal to create new plan', (t) => {
    RoomingPlansList.$root.$emit('create-plan');
});

test('create plan modal is open', (t) => {
    t.true(RoomingPlansList.showPlanModal);
});

test('selectedPlan data set', (t) => {
    t.true(RoomingPlansList.selectedPlan.campaign_id === RoomingPlansList.campaignId && RoomingPlansList.selectedPlan.group_id === RoomingPlansList.groupId);
});

test.before('set data for new plan', (t) => {
    RoomingPlansList.selectedNewPlan.name = 'Test New Rooming Plan';
    RoomingPlansList.selectedNewPlan.short_desc = 'Short Description';
});

test('create new plan', t => {
    RoomingPlansList.newPlan().then(function (response) {
        t.deepEqual(RoomingPlansList.currentPlan, response)
    });
});
