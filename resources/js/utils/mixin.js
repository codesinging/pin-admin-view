import state from "./state";

const module = {
    data() {
        return {
            state: state.state,
        }
    },

    methods: {
        set(key, value) {
            state.set(key, value)
        },

        get(key, def) {
            return state.get(key, def)
        },

        setTrue(key) {
            state.setTrue(key)
        },

        setFalse(key) {
            state.setFalse(key)
        },

        toggle(key) {
            state.toggle(key)
        }
    }
}

export default module