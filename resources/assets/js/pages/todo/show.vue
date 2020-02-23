<template>
    <div>
        <div class="row mb-3">
            <div class="col">

            </div>
            <div class="col-md-auto d-flex">
                <button type="submit" class="btn btn-secondary pointer mr-1" title="Unerledigt" @click="incomplete" v-if="item.completed"><i class="fas fa-fw fa-check-square"></i></button>
                <button type="submit" class="btn btn-secondary pointer mr-1" title="Erledigt" @click="complete" v-else><i class="far fa-fw fa-square"></i></button>
                <a :href="model.path + '/edit'" class="btn btn-secondary mr-1"><i class="fas fa-edit"></i></a>
                <a href="/aufgaben" class="btn btn-secondary">Übersicht</a>
            </div>
        </div>

        <div class="row">

            <div class="col-md-8 col-main">

                <card :item="item" @completed="item.completed = $event"></card>

                <div class="card mb-3" v-if="item.times">
                    <div class="card-header">Zeiten</div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Mitarbeiter</th>
                                    <th>Diensleistung</th>
                                    <th>Dauer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(time, index) in item.times">
                                    <td>{{ formatDatetime(time.start_at) }}</td>
                                    <td>{{ time.user.name }}</td>
                                    <td>{{ time.item.name }}</td>
                                    <td>{{ time.end_at == null ? 'Läuft' : time.formatedHours }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Kommentare</div>
                    <div class="card-body">
                        <comments uri="/aufgaben" :item="item"></comments>
                    </div>
                </div>

            </div>

            <div class="col-md-4 col-side">

                <contacts :model="item"></contacts>

                <todoable :model="item.todoable" :type="item.todoable_type"></todoable>

                <div class="card mb-3">
                    <div class="card-header">Dateien</div>
                    <div class="card-body">
                        <userfileable-table uri="/aufgaben" :model="item"></userfileable-table>
                    </div>
                </div>

                <note :model="item"></note>

                <activity></activity>

            </div>

        </div>
    </div>
</template>

<script>
    import moment from "moment";

    import activity from '../../components/todo/activity/index.vue';
    import card from '../../components/project/todo/card.vue';
    import contacts from '../../components/todo/task/contacts.vue';
    import note from '../../components/todo/note/card.vue';
    import todoable from '../../components/todo/todoable/show.vue';

    export default {

        components: {
            activity,
            card,
            contacts,
            note,
            todoable,
        },

        props: {
            model: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                item: this.model,
            };
        },

        methods: {
            complete() {
                var component = this;
                axios.post(component.model.path + '/erledigt')
                    .then(function (response) {
                        Vue.set(component.item, 'completed', true);
                        Vue.success('Aufgabe ist erledigt');
                    })
                    .catch (function (error) {
                        console.log(error);
                });
            },
            incomplete() {
                var component = this;
                axios.delete(component.model.path + '/erledigt')
                    .then(function (response) {
                        Vue.set(component.item, 'completed', false);
                        Vue.success('Aufgabe ist unerledigt');
                    })
                    .catch (function (error) {
                        console.log(error);
                });
            },
            formatDatetime(date) {
                return moment(date).format('DD.MM.YYYY HH:mm');
            },
        },
    };
</script>