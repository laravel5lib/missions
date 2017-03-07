/**
 * Created by jerezb on 2017-02-23.
 */
// Source: https://github.com/letsrock-today/mock-local-storage/blob/master/src/mock-localstorage.js
// Mock localStorage and sessionStorage
(function (glob) {

    function createStorage() {
        let s = {},
            noopCallback = () => {},
            _itemInsertionCallback = noopCallback;

        Object.defineProperty(s, 'setItem', {
            get: () => {
                return (k, v) => {
                    k = k + '';
                    if (!s.hasOwnProperty(k)) {
                        _itemInsertionCallback(s.length);
                    }
                    s[k] = v + '';
                };
            }
        });
        Object.defineProperty(s, 'getItem', {
            get: () => {
                return k => {
                    k = k + '';
                    if (s.hasOwnProperty(k)) {
                        return s[k];
                    } else {
                        return null;
                    }
                };
            }
        });
        Object.defineProperty(s, 'removeItem', {
            get: () => {
                return k => {
                    k = k + '';
                    if (s.hasOwnProperty(k)) {
                        delete s[k];
                    }
                };
            }
        });
        Object.defineProperty(s, 'clear', {
            get: () => {
                return () => {
                    for (let k in s) {
                        if (s.hasOwnProperty(k)) {
                            delete s[k];
                        }
                    }
                };
            }
        });
        Object.defineProperty(s, 'length', {
            get: () => {
                return Object.keys(s).length;
            }
        });
        Object.defineProperty(s, "key", {
            value: k => {
                let key = Object.keys(s)[k];
                return (!key) ? null : key;
            },
        });
        Object.defineProperty(s, 'itemInsertionCallback', {
            get: () => {
                return _itemInsertionCallback;
            },
            set: v => {
                if (!v || typeof v != 'function') {
                    v = noopCallback;
                }
                _itemInsertionCallback = v;
            }
        });
        return s;
    }

    glob.localStorage = createStorage();
    glob.sessionStorage = createStorage();
}(typeof window !== 'undefined' ? window : global));



/*var cookieStorage = {
    cookieStorageValue_: '',

    get cookie() {
        return this.cookieStorageValue_;
    },

    set cookie(value) {
        this.cookieStorageValue_ += value + ';';
    }
};*/
Object.defineProperty(document, 'cookieStorageValue_', {
    value: '',
    writable: true
});
Object.defineProperty(document, 'cookie', {
    get: function() {
        return this.cookieStorageValue_;
    },

    set: function(value) {
        this.cookieStorageValue_ += value + ';';
    }
});

window.$ = window.jQuery = require('jquery');
require('jquery.cookie');