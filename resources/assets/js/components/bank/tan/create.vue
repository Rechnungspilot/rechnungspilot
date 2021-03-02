<template>
    <div class="modal fade show" :class="{show: tan.show}"tabindex="-1" role="dialog" id="bank-company-create" :style="'display: ' + (tan.show ? 'block' : 'none') + ';'">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tan eingeben</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-html="tan.html"></div>
                        <div class="form-group">
                            <label>Tan</label>
                            <input type="text" v-model="tan.tan" class="form-control" :class="'tan' in errors ? 'is-invalid' : ''" placeholder="Tan für Onlinebanking" autofocus></input>
                            <div class="invalid-feedback" v-text="'tan' in errors ? errors.tan[0] : ''"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="submitTan">Tan absenden</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {

        components: {

        },

        props: {
            tan: {
                type: Object,
                required: true,
            }
        },

        data() {
            return {
                errors: {},
            };
        },

        methods: {
            submitTan() {
                var component = this;
                axios.post('/bank/konten/' + component.tan.bank_company_id + '/tan', component.tan)
                    .then(function (response) {
                        console.log(response);
                        // TODO: antwort überprüfen
                        // Modal schließen und weiter machen
                        this.$emit('success');
                    });
            },
        },

    };
</script>