/**
 * Created by jerezb on 2017-04-25.
 */
//load the component with a vue instance
RootInstance.template = '<div><team-manager v-ref:test-component :id="userId"></team-manager></div>';
RootInstance.components = { teamManager };
const vm = new Vue(RootInstance).$mount();

let teamManagerComponent = vm.$refs.testComponent;

test.before('should login successfully', (t) => {
    t.pass();
});