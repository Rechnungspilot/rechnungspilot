<template>
    <div class="row">

        <div class="col-md-6">

            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Vorlage</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <select class="form-control" id="type" v-model="item.type" @change="update">
                            <option v-for="(option, key) in templates" :value="key">{{ option }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Briefkopf</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <select class="form-control" id="header_type" v-model="item.header_type" @change="update">
                            <option v-for="(option, key) in headers" :value="key">{{ option }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Logo</h4>
                </div>
                <div class="card-body">
                    <img :src="model.url" height="50" v-show="item.logo">
                    <form :action="actionLogo" class="form-inline" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" :value="token">
                        <div class="form-group">
                            <input type="file" name="file" class="form-control-file" id="file">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <button type="submit" class="btn btn-primary mr-1" title="Anlegen"><i class="fas fa-plus-square"></i></button>
                            <button type="button" class="btn btn-secondary" title="Löschen" @click.prevent="deleteLogo" v-show="item.logo"><i class="fas fa-trash"></i></button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Fußzeile</h4>
                </div>
                <div class="card-body">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="show_footer" v-model="item.show_footer" value="1" @change="update">
                        <label for="show_footer">Fußzeile anzeigen</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <object :data="link" style="width: 100%; height: 600px">
                <center>PDF kann nicht angezeigt werden.</center>
            </object>
        </div>

    </div>
</template>

<script>

    export default {

        props: [
            'model',
            'token',
            'templates',
            'headers',
        ],

        data() {
            return {
                actionLogo: this.model.path + '/logo',
                i: 0,
                item: this.model,
            };
        },

        computed: {
            link() {
                return '/belege/vorlage?i=' + this.i;
            }
        },

        methods: {
            update(event) {
                var component = this,
                    data = {};

                data[event.target.id] = component.item[event.target.id];
                axios.put(this.model.path, data)
                    .then( function (response) {
                        component.i++;
                });
            },
            deleteLogo() {
                var component = this;
                axios.delete(this.actionLogo)
                    .then( function (response) {
                        component.item.logo = '';
                        component.i++;
                });
            },
        },

    };

</script>