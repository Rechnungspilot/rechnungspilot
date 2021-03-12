<template>
    <div>
        <input class="form-control form-control-sm" :class="error ? 'is-invalid' : ''" type="text" v-model="display">
        <div class="invalid-feedback" v-text="error ? error : ''"></div>
    </div>
</template>

<script>
    export default {

        props: [
            'error',
            'value',
        ],

        computed: {
            display: {
                get() {
                    return this.value.format(this.value.neededDecimals(0, 2), ',', '');
                },
                set(value) {
                    this.$emit('input', this.parse(value));
                },
            },
        },

        data() {
            return {

            };
        },

        methods: {
            parse(value) {
                if (value == '') {
                    return 0;
                }
                var number = Number(value.replace(/\./g, '').replace(',', '.'));
                return number ? number : 0;
            },
        },

    };
</script>