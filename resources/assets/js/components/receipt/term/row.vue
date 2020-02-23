<template>
    <tr>
        <td class="align-middle pointer">{{ item.name }}</td>
        <td class="align-middle text-right pointer">{{ item.days }}</td>
        <td class="align-middle pointer" v-text="item.default ? 'Ja' : 'Nein'"></td>
        <td class="text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" @click="setDefault" v-show="item.default == 0">Standard</button>
                <a :href="link" class="btn btn-secondary" title="Bearbeiten"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-show="item.default == 0"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [
            'item',
            'type',
        ],

        data () {
            return {
                id: this.item.id,
                link: '/terms/' + this.type + '/' + this.item.id + '/edit',
            };
        },

        methods: {
            destroy() {
                axios.delete('/terms/' + this.id);
                this.$emit("deleted", this.id);
            },
            setDefault() {
                var component = this;
                axios.post('/terms/' + this.type + '/' + this.id + '/default')
                    .then( function (response) {
                        component.$emit("setDefault", component.id);
                });
            },
        },
    };
</script>