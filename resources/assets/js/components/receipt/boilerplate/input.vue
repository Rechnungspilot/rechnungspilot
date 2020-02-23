<template>
    <div>
        <div class="form-group">
            <label for="text_above">Text über Positionen <a href="/textbausteine"><i class="fas fa-external-link-alt"></i></a></label>
            <div class="form-row mb-1">
                <div class="col">
                    <select class="form-control" v-model="boilerplateAbove" @change="appendBoilerplateAbove" v-show="boilerplates.length">
                        <option value="0">Textbaustein hinzufügen</option>
                        <option v-for="(option, key) in boilerplates" :value="key">{{ option.name }}</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" v-model="placeholderAbove" @change="appendPlaceholderAbove">
                        <option value="0">Platzhalter hinzufügen</option>
                        <option v-for="(option, key) in placeholders" :value="key">{{ option }}</option>
                    </select>
                </div>
            </div>
            <editor-input name="text_above" v-model="text_above"></editor-input>
        </div>

        <div class="form-group">
            <label for="text_below">Text unter Positionen <a href="/textbausteine"><i class="fas fa-external-link-alt"></i></a></label>
            <div class="form-row mb-1">
                <div class="col">
                    <select class="form-control" v-model="boilerplateBelow" @change="appendBoilerplateBelow" v-show="boilerplates.length">
                        <option value="0">Textbaustein hinzufügen</option>
                        <option v-for="(option, key) in boilerplates" :value="key">{{ option.name }}</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" v-model="placeholderBelow" @change="appendPlaceholderBelow">
                        <option value="0">Platzhalter hinzufügen</option>
                        <option v-for="(option, key) in placeholders" :value="key">{{ option }}</option>
                    </select>
                </div>
            </div>
            <editor-input name="text_below" v-model="text_below"></editor-input>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'textAboveProp',
            'textBelowProp',
            'placeholders',
            'boilerplates',
        ],

        data() {
            return {
                placeholderAbove: 0,
                placeholderBelow: 0,
                boilerplateAbove: 0,
                boilerplateBelow: 0,
                text_above: this.textAboveProp,
                text_below: this.textBelowProp,
            };

        },

        methods: {
            appendPlaceholderAbove() {
                if (this.placeholderAbove == 0) return;
                this.text_above = this.text_above + ' <div>' + this.placeholderAbove + '<br /></div>';
                this.placeholderAbove = 0;
            },
            appendPlaceholderBelow() {
                if (this.placeholderBelow == 0) return;
                this.text_below = this.text_below + ' <div>' + this.placeholderBelow + '<br /></div>';
                this.placeholderBelow = 0;
            },
            appendBoilerplateAbove() {
                if (this.boilerplateAbove == 0) return;
                this.text_above = (this.text_above ? this.text_above + "\n" : '') + this.boilerplates[this.boilerplateAbove].text + '';
                this.boilerplateAbove = 0;
            },
            appendBoilerplateBelow() {
                if (this.boilerplateBelow == 0) return;
                this.text_below = (this.text_below ? this.text_below + "\n" : '') +  this.boilerplates[this.boilerplateBelow].text + '';
                this.boilerplateBelow = 0;
            },
        },
    };
</script>