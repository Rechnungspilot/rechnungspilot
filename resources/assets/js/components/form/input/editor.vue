<template>
    <div class="form-group">
        <wysiwyg v-model="value" />
        <input type="hidden" :name="name" v-model="value">
        <div class="invalid-feedback" v-text="error ? error : ''" :style="{ display: error ? 'block' : 'none;' }"></div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: [
            'error',
            'name',
            'value',
        ],

    };
</script>
<style>
    .editr--content
    {
        display: block;
        width: 100%;
        /*height: calc(1.6em + 0.75rem + 2px);*/
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>