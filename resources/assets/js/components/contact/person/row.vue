<template>

    <show :item="item" :is-selected="isSelected" :has-show-button="false" @destroying="destroy()">

        <template v-slot:show>

            <td class="align-middle pointer">{{ item.title }}</td>
            <td class="align-middle pointer">{{ item.lastname }}</td>
            <td class="align-middle pointer">{{ item.firstname }}</td>
            <td class="align-middle pointer">{{ item.phonenumber }}</td>
            <td class="align-middle pointer">{{ item.mobilnumber }}</td>
            <td class="align-middle pointer">{{ item.email }}</td>
            <td class="align-middle pointer">{{ item.function }}</td>
            <td class="align-middle pointer">
                <label class="form-checkbox"></label>
                <input :checked="item.default_quote" type="checkbox" @change="setDefault('quote', $event)" number>
            </td>
            <td class="align-middle pointer">
                <label class="form-checkbox"></label>
                <input :checked="item.default_invoice" type="checkbox" @change="setDefault('invoice', $event)" number>
            </td>

        </template>

    </show>

</template>

<script>
    import show from '../../tables/rows/show.vue';

    import { showMixin } from "../../../mixins/tables/rows/show.js";

    export default {

        components: {
            show,
        },

        mixins: [
            showMixin,
        ],

        data () {
            return {

            };
        },

        methods: {
            setDefault(type, event) {
                var component = this;
                if (event.target.checked) {
                    axios.post(component.item.path +  '/default/' + type)
                        .then( function (response) {
                            component.$emit('setDefault', type, component.item.id, event.target.checked);
                            Vue.success('Standard gesetzt');
                    });
                }
                else {
                    axios.delete(component.item.path + '/default/' + type)
                        .then( function (response) {
                            component.$emit('setDefault', type, component.item.id, event.target.checked);
                            Vue.success('Standard entfernt');
                    });
                }
            },
        },
    };
</script>