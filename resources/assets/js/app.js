
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

Number.prototype.neededDecimals = function (min, max) {
        var number = this.toString().replace(/0+$/, '');
        var pos = number.indexOf('.') + 1;
        var decimals = pos == 0 ? 0 : number.substring(pos).length;

        return (decimals < min ? min : (decimals > max ? max : decimals));
};

/**
 * Number.prototype.format(n, x, s, c)
 *
 * @param integer n: length of decimal
 * @param integer x: length of whole part
 * @param mixed   s: sections delimiter
 * @param mixed   c: decimal delimiter
 */
Number.prototype.format = function(decimals, dec_point, thousands_sep) {
    var number = (this + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return '' + (Math.round(n * k) / k).toFixed(prec);
    };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};

var Highcharts = require('highcharts');

Highcharts.setOptions({
    lang: {
        decimalPoint: ',',
        thousandsSep: '.'
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import Datepicker from 'vuejs-datepicker';

import Datetime from 'vue-datetime';
import 'vue-datetime/dist/vue-datetime.css';
import Flash from './plugins/flash.js'
import { Settings } from 'luxon';
import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {
    hideModules: {
        "image": true,
    },
});

Settings.defaultLocale = 'de';

Vue.use(Datetime);
Vue.use(Flash);

Vue.component('abo-contacts-select', require('./components/receipt/abo/contacts.vue').default);
Vue.component('abo-settings', require('./components/receipt/abo/settings/edit.vue').default);
Vue.component('account-table', require('./components/account/table.vue').default);
Vue.component('address-select', require('./components/receipt/address/select.vue').default);
Vue.component('appointment-table', require('./components/todo/appointment/table.vue').default);
Vue.component('balance-create-table', require('./components/balance/create/table.vue').default);
Vue.component('boilerplate-input', require('./components/receipt/boilerplate/input.vue').default);
Vue.component('boilerplate-table', require('./components/receipt/boilerplate/table.vue').default);
Vue.component('calendar', require('./pages/calendar/index.vue').default);
Vue.component('comments', require('./components/comment/index.vue').default);
Vue.component('contact-revenue-chart', require('./components/contact/revenue.vue').default);
Vue.component('contact-table', require('./components/contact/table.vue').default);
Vue.component('customfield-table', require('./components/customfield/table.vue').default);
Vue.component('date-input', require('./components/form/input/date.vue').default);
Vue.component('dates-edit', require('./components/receipt/date/edit.vue').default);
Vue.component('datetime-input', require('./components/form/input/datetime.vue').default);
Vue.component('dun-table', require('./components/receipt/dun/table.vue').default);
Vue.component('flash-message', require('./components/flashmessage.vue').default);
Vue.component('invoice-partials', require('./components/receipt/invoice/partials.vue').default);
Vue.component('item-revenue-chart', require('./components/item/revenue.vue').default);
Vue.component('item-table', require('./components/item/table.vue').default);
Vue.component('letter-table', require('./components/receipt/letter/table.vue').default);
Vue.component('order-select', require('./components/form/input/receipt/order/select.vue').default);
Vue.component('person-table', require('./components/contact/person/table.vue').default);
Vue.component('project-show', require('./components/project/show.vue').default);
Vue.component('projectgroups', require('./components/project/group/index.vue').default);
Vue.component('receipt-item-table', require('./components/receipt/item/table.vue').default);
Vue.component('receipt-table', require('./components/receipt/table.vue').default);
Vue.component('tag-select', require('./components/tag/select.vue').default);
Vue.component('tag-table', require('./components/tag/table.vue').default);
Vue.component('task-contacts-select', require('./components/todo/task/contacts.vue').default);
Vue.component('task-table', require('./components/todo/task/table.vue').default);
Vue.component('template-edit', require('./components/template/edit.vue').default);
Vue.component('term-table', require('./components/receipt/term/table.vue').default);
Vue.component('time-show', require('./components/time/show.vue').default);
Vue.component('time-table', require('./components/time/table.vue').default);
Vue.component('todo-edit', require('./pages/todo/edit.vue').default);
Vue.component('todo-show', require('./pages/todo/show.vue').default);
Vue.component('transaction-company-table', require('./components/transaction/company/table.vue').default);
Vue.component('transaction-table', require('./components/transaction/table.vue').default);
Vue.component('items-units-table', require('./components/items/units/table.vue').default);
Vue.component('items-articles-table', require('./components/items/articles/table.vue').default);
Vue.component('user-table', require('./components/user/table.vue').default);
Vue.component('userfile-table', require('./components/userfile/table.vue').default);
Vue.component('userfileable-receipt-single', require('./components/receipt/userfile/index.vue').default);
Vue.component('userfileable-table', require('./components/userfileable/table.vue').default);
Vue.component('interaction-table', require('./components/contact/interaction/table.vue').default);
Vue.component('payment-table', require('./components/receipt/payment/table.vue').default);
Vue.component('editor-input', require('./components/form/input/editor.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
$(document).ready( function () {
    const app = new Vue({
        el: '#app',
        components: {
            Datepicker
        },
    });

    // show active tab on reload
    if (location.hash !== '') $('a[href="' + location.hash + '"]').tab('show');

    // remember the hash in the URL without jumping
    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        if(history.pushState) {
            history.pushState(null, null, '#'+$(e.target).attr('href').substr(1));
        } else {
            location.hash = '#'+$(e.target).attr('href').substr(1);
        }
    });

    $('#menu-toggle').click(function() {
        $('#nav, #content-container').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('.collapse', 'nav#nav').on('show.bs.collapse', function(){
        $('a[data-target="#' + $(this).attr('id') +'"] i.fas', 'nav#nav').toggleClass("fa-caret-right fa-caret-down");
    }).on('hide.bs.collapse', function(){
        $('a[data-target="#' + $(this).attr('id') +'"] i.fas', 'nav#nav').toggleClass("fa-caret-down fa-caret-right");
    });

    $('i.fas[data-toggle="popover"]').popover();
})
