/**
 * Created by jerezb on 2017-06-26.
 */
import {Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage} from '../../../vue-with-api.config';
import nextTick from "p-immediate";
import test from "ava";

//load the component with a vue instance
RootInstance.template = '<div><filters-indicator v-ref:test-component :filters="$root.someObject"></filters-indicator></div>';
RootInstance.components = {'filters-indicator': require('../../../../components/filters/filters-indicator.vue')};
document.body.insertAdjacentHTML("afterbegin", "<app></app>");
const vm = new Vue(RootInstance).$mount('app');
let FiltersIndicator = vm.$refs.testComponent;

vm.someObject = {
    type: '',
    //tags: [],
    user: [],
    groups: [],
    campaign: null,
    gender: '',
    status: '',
    shirtSize: [],
    hasCompanions: null,
    due: '',
    todoName: '',
    todoStatus: null,
    designation: '',
    requirementName: '',
    requirementStatus: '',
    dueName: '',
    dueStatus: '',
    rep: '',
    age: [0, 120],
    minPercentRaised: '',
    maxPercentRaised: '',
    minAmountRaised: '',
    maxAmountRaised: ''
};

test('test filters object property', t => {
    t.true(FiltersIndicator.propertyExists('user'));
    // Not much to test here...

});