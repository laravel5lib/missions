/**
 * Created by jerezb on 2017-02-23.
 */
const browserEnv = require('browser-env');
const hooks = require('require-extension-hooks');
const Vue = require('vue');
const hook = require('vue-node');
const { join } = require('path');

// Setup a fake browser environment
browserEnv(['window', 'document', 'navigator', 'location', 'localStorage', 'XMLHttpRequest', 'cookie', 'URL', 'Blob', 'HTMLCanvasElement', 'Document']);

// Setup Vue.js to remove production tip
Vue.config.productionTip = false;

// Setup vue files to be processed by `require-extension-hooks-vue`
hooks('vue').plugin('vue').push();
// Setup vue and js files to be processed by `require-extension-hooks-babel`
hooks(['vue', 'js']).plugin('babel').push();

// Pass an absolute path to your webpack configuration to the hook function.
hook(join(__dirname, '../webpack.config.test.js'));