<template>
    <tr>
        <th class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </th>
        <td class="align-middle pointer" @click="link(false)">{{ item.number }}</td>
        <td class="align-middle pointer" @click="link(false)">
            {{ item.name }}
            <div v-html="item.tags_badges"></div>
        </td>
        <td class="align-middle pointer text-right" @click="link(false)">{{ (parseFloat(item.unit_price) * (1 + parseFloat(item.tax))).format(2, ',', '.') }} €</td>
        <td class="align-middle pointer text-right" @click="link(false)">{{ parseFloat(item.unit_price).format(2, ',', '.') }} €</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.unit.abbreviation }}</td>
        <td class="align-middle pointer" @click="link(false)">{{ item.tax * 100 }}%</td>
        <td class="align-middle pointer text-right" @click="link(false)">{{ (item.revenue / 100).format(2, ',', '.') }} €</td>
        <td class="align-middle text-right">
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