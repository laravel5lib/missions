// save expiring state in local storage and restore that state
import expiringStorage from './expiring-storage';
import { pick } from './helpers';
export default {
    props: {
        cacheKey: { default: null },
        cacheLifetime: { default: 5 }
    },

    data() {
        return {
            history: null
        }
    },

    computed: {
        storageKey() {
            return this.cacheKey
                ? `mm-component.${this.cacheKey}`
                : `mm-component.${window.location.host}${window.location.pathname}${this.cacheKey}`;
        }
    },

    methods: {
        saveState(vars = []) {
            expiringStorage.set(this.storageKey, pick(this.$data, vars), this.cacheLifetime);
        },
        restoreState(vars = []) {
            const previousState = expiringStorage.get(this.storageKey);

            if (previousState === null) {
                return;
            }

            this.saveState();

            return previousState;
        }
    }
}