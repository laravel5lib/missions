/**
 * Created by jerezb on 2017-02-23.
 */
const browserEnv = require('browser-env');
const hook = require('vue-node');
const { join } = require('path');

// Setup a fake browser environment
browserEnv(['window', 'document', 'navigator', 'location', 'localStorage', 'XMLHttpRequest', 'cookie', 'URL', 'Blob', 'HTMLCanvasElement', 'Document']);

// Pass an absolute path to your webpack configuration to the hook function.
hook(join(__dirname, '../webpack.config.test.js'));