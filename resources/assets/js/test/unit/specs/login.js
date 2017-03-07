/**
 * Created by jerezb on 2017-02-23.
 */
import { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage } from '../../vue-with-api.config';
import nextTick from 'p-immediate';
import test from 'ava';
// import login from '../../../components/login.vue';

//load the component with a vue instance
RootInstance.template = '<div><login v-ref:test-component></login></div>';
RootInstance.components = { 'login' : require('../../../components/login.vue') };
const vm = new Vue(RootInstance).$mount();

let loginComponent = vm.$refs.testComponent;
let event = {
    preventDefault(){

    }
};


/*test.before('should login successfully', (t) => {
    console.log(vm.user);
});*/
test.before('set user data', async (t) => {
    loginComponent.user.email = 'admin@admin.com';
    loginComponent.user.password = 'secret';
    // loginComponent.user._token = '';
    await nextTick();
});

test('should attempt login user', async (t) => {
    // login form is valid
    t.true(loginComponent.$LoginForm.valid);

    loginComponent.attempt(event);
    await nextTick();

    if (loginComponent.messages.length) {
        console.log(loginComponent.messages);
        t.fail();
    } else {
        t.pass();
    }

});

test('should get user data', (t) => {
    loginComponent.getUserData(null, true).then(result => {
        t.true(_.isObject(result) && result.hasOwnProperty('id'));
    });
});

// TODO: test user registration
/*
test.before('set register data', async t => {
    loginComponent.newUser.email = 'admin@admin.com';
    loginComponent.newUser.password = 'secret';

});

test('should register user', async (t) => {
    loginComponent.registerUser(event);
    await nextTick();
});
*/
