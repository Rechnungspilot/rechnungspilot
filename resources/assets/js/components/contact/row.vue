<template>
    <tr>
        <th class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </th>
        <td class="align-middle pointer" @click="link(false)">{{ item.name }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.address }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.postcode }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.city }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.tagsString }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ (item.revenue / 100).format(2, ',', '.') }} €</td>
        <td class="text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Anzeigen" @click="link(false)"><i class="fas fa-fw fa-eye"></i></button>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="link(true)"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [
            'item',
            'uri',
            'selected',
        ],

        data () {
            return {
                id: this.item.id,
            };
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit("deleted", this.id);
            },
            link (edit) {
                location.href = this.item.path + (edit ? '/edit' : '');
            }
        },
    };
</script>