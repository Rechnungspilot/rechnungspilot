<template>
    <tr>
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
            <input :checked="item.default_invoice" type="checkbox" @change="setDefault('invoice', $event)" number></td>
        <td class="text-right">
            <div class="btn-group btn-group-sm" role="group">
                <a :href="link" class="btn btn-secondary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [
            'item',
        ],

        data () {
            var uri = '/kontakte/ansprechpartner';
            return {
                id: this.item.id,
                link: uri + '/' + this.item.id + '/edit',
                uri: uri,
            };
        },

        methods: {
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            setDefault(type, event) {
                var component = this;
                if (event.target.checked) {
                    axios.post(this.uri + '/' + this.id + '/default/' + type)
                        .then( function (response) {
                            component.$emit("setDefault", type, component.id, event.target.checked);
                    });
                }
                else {
                    axios.delete(this.uri + '/' + this.id + '/default/' + type)
                        .then( function (response) {
                            component.$emit("setDefault", type, component.id, event.target.checked);
                    });
                }
            },
        },
    };
</script>