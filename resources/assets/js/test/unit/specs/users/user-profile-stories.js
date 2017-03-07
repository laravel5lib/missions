/**
 * Created by jerezb on 2017-02-23.
 */
import { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage } from '../../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';
import userProfileStories from '../../../../components/users/user-profile-stories.vue';

//load the component with a vue instance
RootInstance.template = '<div><user-profile-stories v-ref:test-component :id="userId"></user-profile-stories></div>';
RootInstance.components = { userProfileStories };
const vm = new Vue(RootInstance).$mount();

let userProfileStoriesComponent = vm.$refs.testComponent;

test.before('should login successfully', (t) => {
    t.pass();
});