<template>
    <div class="d-flex flex-column align-items-stretch" style="height: calc(100% - 50px);">
        <div class="row">
            <div class="col d-flex align-items-center">
                <div class="mr-3">
                    <button class="btn btn-sm btn-secondary" @click="toToday">Heute</button>
                </div>
                <div class="mx-3">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-secondary" @click="prev"><i class="fas fa-fw fa-chevron-left"></i></button>
                        <button class="btn btn-secondary" @click="next"><i class="fas fa-fw fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="mx-3 d-flex align-items-center ">
                    <span id="date">{{ calendar.month_name }} {{ calendar.year }}</span> <span class="badge badge-secondary ml-1">{{ calendar.week_of_year }}</span>
                </div>
            </div>
            <div class="col-auto d-flex align-items-center">
                <div class="form-group form-check mb-0" v-show="filter.type == 'week'">
                    <input type="checkbox" class="form-check-input" id="show_weekend" value="1" v-model="filter.show_weekend">
                    <label class="form-check-label" for="show_weekend">Wochende</label>
                </div>
                <div class="form-group ml-3 mb-0">
                    <select v-model="filter.type" class="form-control" @input="fetch()">
                        <option value="day">Tag</option>
                        <option value="week">Woche</option>
                        <option value="month">Monat</option>
                        <!-- <option value="customday">4 Tage</option> -->
                    </select>
                </div>
            </div>
        </div>

        <div class="col row">
            <div class="col-auto">
                <div class="title text-left d-flex align-items-center">
                    <button class="btn btn-primary" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
                <div>
                    <!-- Kalender -->
                </div>
                <ul class="list-group mt-0 mb-3">
                    <li class="list-group-item" style="border-left: 5px solid transparent">
                        <div class="form-group form-check my-0 pointer">
                            <input type="checkbox" class="form-check-input" id="user0" :checked="(filter.users.indexOf(0) != -1)" @input="toggleUser(0)">
                            <label class="form-check-label" for="user_0">Nicht zugeordnet</label>
                        </div>
                    </li>
                    <li class="list-group-item" :style="{'border-left': '5px solid ' + user.hex_color_code}" :key="user.id" v-for="(user, index) in users">
                        <div class="form-group form-check my-0 pointer">
                            <input type="checkbox" class="form-check-input" :id="'user_' + user.id" :checked="(filter.users.indexOf(user.id) != -1)" @input="toggleUser(user.id)">
                            <label class="form-check-label" :for="'user_' + user.id">{{ user.name }}</label>
                        </div>
                    </li>
                </ul>

                <order-filter @input="changeOrder($event)"></order-filter>

            </div>
            <div class="col calendar">
                <months v-if="filter.type == 'month'" :days="days" :events-per-day="eventsPerDay" @creating="creating($event)" @updating="updating($event)"></months>
                <div v-else>
                    <div class="calendar-header row">
                        <div class="col" style="max-width: 80px;">

                        </div>
                        <div class="col" v-for="(day, index) in days">
                            <h2 class="title d-flex align-items-center justify-content-center flex-column" :class="{today: isToday(day.date)}">
                                <div class="name">
                                    {{ day.name }}
                                </div>
                                <div class="day pointer" @click="showDay(day.date)">
                                    {{ day.day }}
                                </div>
                            </h2>
                        </div>
                    </div>
                    <div class="row mb-3" v-show="initialDays">
                        <div class="col" style="max-width: 80px;">
                            <div class="hour" v-for="hour in hours">
                                <span>{{ hour }}</span>
                            </div>
                        </div>
                        <div class="grid-lines">
                            <div class="grid-line hour-height" v-for="hour in hours">
                            </div>
                        </div>
                        <day :day="day" :events-raw="(index in eventsPerDay) ? eventsPerDay[index] : []" :key="index" v-for="(day, index) in days" @creating="creating($event)" @updating="updating($event)" @updatingEndAt="updatingEndAt"></day>
                    </div>
                </div>
                <create :initial-start-at="createAttributes.start_at" :users="users" @created="created"></create>
                <edit :todo="edit.item" :users="users" @updated="updated" @deleted="deleted"></edit>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    import create from '../../components/calendar/todo/create.vue';
    import day from '../../components/calendar/day/show.vue';
    import edit from '../../components/calendar/todo/edit.vue';
    import months from '../../components/calendar/month/index.vue';
    import orderFilter from '../../components/filter/receipt/order.vue';

    export default {

        components: {
            create,
            day,
            edit,
            months,
            orderFilter,
        },

        props: {
            users: {
                type: Array,
                required: true,
            },
        },

        data() {
            var userIds = [0];
            for(var index in this.users) {
                userIds.push(this.users[index].id);
            }
            return {
                calendar: {
                    week_of_year: '',
                    month_name: '',
                    year: '',
                },
                events: [],
                initialDays: null,
                hours: [...Array(24).keys()],
                createAttributes: {
                    start_at: '',
                },
                edit: {
                    index: 0,
                    item: {},
                },
                filter: {
                    type: 'week',
                    show_weekend: false,
                    date: new Date(),
                    users: userIds,
                    order_id: null,
                },
                today: new Date(),
            };
        },

        watch: {
            show_weekend(newValue, oldValue) {
                this.fetch();
            },
        },

        computed: {
            days() {
                return this.initialDays || {};
            },
            eventsPerDay() {
                var events = {};
                for(var index in this.events) {
                    var event = _.clone(this.events[index], true);
                    event.index = index;
                    for (var index in event.days) {
                        var date = event.days[index];
                        if (!(date in events)) {
                            events[date] = [];
                        }
                        events[date].push(event);
                    }
                }

                return events;
            },
            show_weekend() {
                return this.filter.show_weekend;
            },
        },

        mounted() {
            this.fetch();
        },

        methods: {
            changeOrder(order) {
                this.filter.order_id = order ? order.id : null;
                this.fetch();
            },
            create() {
                this.createAttributes.start_at = (new Date()).toISOString();
                $('#todo-create').modal('show');
            },
            creating(start_at) {
                this.createAttributes.start_at = start_at.toISOString();
                $('#todo-create').modal('show');
            },
            created(todo) {
                this.events.push(todo);
            },
            updating(data) {
                this.edit.index = data.todo.index;
                this.edit.item = data.todo;
                $('#todo-edit').modal('show');
            },
            updated(todo) {
                Vue.set(this.events, this.edit.index, todo);
            },
            updatingEndAt({index, seconds}) {
                this.events[index].end_at = moment(this.events[index].end_at).add(seconds, 'seconds').toDate();
            },
            deleted(todo) {
                this.events.splice(this.edit.index, 1);
            },
            toDateObject(datetime) {

            },
            fetch() {
                var component = this;
                axios.get('/kalender', {
                    params: component.filter
                })
                    .then( function (response) {
                        var calendar = response.data;
                        component.initialDays = calendar.days;
                        component.events = calendar.events;
                        delete calendar.days;
                        component.calendar = calendar;
                        component.filter.date = calendar.date;
                    });

            },
            next() {
                this.filter.date = moment(this.filter.date).add(1, this.filter.type).toDate();
                this.fetch();
            },
            prev() {
                this.filter.date = moment(this.filter.date).subtract(1, this.filter.type).toDate();
                this.fetch();
            },
            showDay(date) {
                this.filter.type = 'day';
                this.filter.date = date;
                this.fetch();
            },
            toToday() {
                this.filter.date = this.today;
                this.fetch();
            },
            isToday(date) {
                date = new Date(date);
                return (date.toDateString() == this.today.toDateString());
            },
            toggleUser(id) {
                var index = this.filter.users.indexOf(id);
                console.log(index, id);
                if (index == -1) {
                    this.filter.users.push(id);
                }
                else {
                    this.filter.users.splice(index, 1);
                }
                this.fetch();
            },
            formatTime(date) {
                return moment(date).format('HH:mm');
            },
        }

    };
</script>

<style scoped>

    #date {
        letter-spacing: 0;
        font-size: 22px;
        line-height: 28px;
        color: #3c4043;
    }

    .hour-height {
        height: 48px;
    }

    .title {
        height: 81px;
        min-height: 81px;
        text-align: center;
        font-weight: 400;
        margin: 0;
    }

    .title > .name {
        color: #70757a;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: .8px;
        margin-left: 0;
        margin-top: 8px;
        text-indent: .8px;
        text-transform: uppercase;
    }

    .title > .day {
        font-size: 26px;
        color: #70757a;
        line-height: 46px;
        border-radius: 100%;
        width: 46px;
        height: 46px;
    }

    .title > .day:hover {
        background-color: #f1f3f4;
        border-bottom: none;
        padding-bottom: 0;
    }

    .title.today > .name {
        color: #1a73e8;
    }

    .title.today > .day {
        background-color: #1a73e8;
        color: white;
    }

    .title.today > .day:hover {
        background-color: #1967d2;
    }

    .hour {
        position: relative;
        height: 48px;
        padding-right: 8px;
        text-align: right;
    }

    .hour span {
        display: block;
        position: relative;
        top: -8px;
        color: #70757a;
        font-size: 10px;
    }

    .grid-lines {
        border-top: #dadce0 1px solid;
    }

    .grid-line {

    }

    .grid-line::after {
        content: '';
        border-bottom: #dadce0 1px solid;
        position: absolute;
        width: calc(100% - 80px);
        margin-top: -1px;
        z-index: 3;
        pointer-events: none;
    }

</style>