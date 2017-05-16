/**
 * Created by jerezb on 2017-05-09.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';

//load the component with a vue instance
RootInstance.template = '<div><rooming-manager v-ref:test-component user-id="112d15e5-c447-4c9e-bf25-b4cdb450c6a2" group-id="0005a7ea-f92f-371e-878a-d28423ea2cfb" ></rooming-manager></div>';
RootInstance.components = {'rooming-manager': require('../../../../components/rooms/rooming-manager.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');

let RoomingManager = vm.$refs.testComponent;
let campaignId = '5830c58b-a183-49ec-a61e-a3c748b33c28';
test.before('set campaign data', (t) => {
    RoomingManager.$http.get('campaigns/' + campaignId).then(function (response) {
        RoomingManager.$root.$emit('campaign-scope', response.body.data);
    }, function (error) {
        t.fail();
    });
});

test('campaign data set', async (t) => {
    await nextTick();
    t.true(RoomingManager.campaignId === campaignId);
});

test.before('set plan data', (t) => {
    RoomingManager.$root.$emit('plan-scope', RoomingManager.plans[0]);
});

test('plan data set', (t) => {
    t.true(RoomingManager.currentPlan.id === RoomingManager.plans[0].id);
});

test.before('open modal to create new plan', (t) => {
    RoomingManager.$root.$emit('create-plan');
});

test('create plan modal is open', (t) => {
    t.true(RoomingManager.showPlanModal);
});

test('selectedPlan data set', (t) => {
    t.true(RoomingManager.selectedPlan.campaign_id === RoomingManager.campaignId && RoomingManager.selectedPlan.group_id === RoomingManager.groupId);
});

test.before('set name for new plan', (t) => {
    RoomingManager.selectedPlan.name = 'Test New Rooming Plan';
});

test('create new plan', t => {
    RoomingManager.newPlan().then(function (response) {
        t.deepEqual(RoomingManager.currentPlan, response)
    });
});

test('open create new room modal', t => {
    RoomingManager.openNewRoomModel();
    t.true(RoomingManager.showRoomModal);
});

test('create new room', t => {
    let type = RoomingManager.roomTypes[0];
    RoomingManager.selectedRoom.room_type_id = type.id;
    RoomingManager.selectedRoom.type = type;
    RoomingManager.selectedRoom.label = 'Cool New Room';

    RoomingManager.newRoom().then(function (room) {
        t.true(!RoomingManager.showRoomModal && room.label === 'Cool New Room');
    });
});

test('set active room', t => {
    RoomingManager.currentRoom = null;
    RoomingManager.setActiveRoom(RoomingManager.currentRooms[0]);
    t.deepEqual(RoomingManager.currentRooms[0], RoomingManager.currentRoom);
});

test('finding room leader', t => {
    t.notThrows(RoomingManager.roomHasLeader(RoomingManager.currentRoom));
});

// TODO test add/remove occupants to rooms
// TODO test promote/demote occupants