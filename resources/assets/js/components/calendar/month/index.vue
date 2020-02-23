<template>
    <div style="height: 100%">
        <div class="row justify-content-center">
            <div class="col d-flex align-items-center justify-content-center" style="max-width: 80px;">KW</div>
            <div class="col text-center border border-transparent">Mo</div>
            <div class="col text-center border border-transparent">Di</div>
            <div class="col text-center border border-transparent">Mi</div>
            <div class="col text-center border border-transparent">Do</div>
            <div class="col text-center border border-transparent">Fr</div>
            <div class="col text-center border border-transparent">Sa</div>
            <div class="col text-center border border-transparent">So</div>
        </div>
        <div class="row flex-column align-items-stretch pr-0" style="height: 100%; margin-right: -30px;">
            <div class="col row pr-0" v-for="(week, weekIndex) in weeks" style="height: 100%">
                <div class="col d-flex align-items-center justify-content-center" style="max-width: 80px;">{{ weekIndex }}</div>
                <div class="col bg-white border" v-for="(day, indexDay) in week" style="height: 100%" @click.stop="createByClick(day.date)">
                    <div class="title" :class="{today: $parent.isToday(day.date)}">
                        <h2 class="day pointer" @click.stop="$parent.showDay(day.date)">{{ day.day == 1 ? day.day + '. ' + day.month_name : day.day }}</h2>
                    </div>
                    <div class="events" style="max-height: 100%; overflow: auto;" @click.stop="">
                        <div class="card shadow pointer" v-for="(event, index) in eventsPerDay[day.dateFormat]" :style="{ 'border-left': '5px solid ' + (event.team ? event.team.hex_color_code : 'transparent'), }" @click.stop="edit(event)">
                            <div class="d-flex justify-content-center align-tems-stretch flex-column px-3">
                                <div> <span>{{ $parent.formatTime(event.start_at) }}</span> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ event.name }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            days: {
                type: Object,
                required: true,
            },
            eventsPerDay: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {

            };
        },

        computed: {
            events() {
                return this.eventsPerDay;
            },
            weeks() {
                var weeks = {},
                    week_of_year = 0,
                    i = 0;
                for (var index in this.days) {
                    if (i % 7 == 0) {
                        week_of_year = this.days[index].week_of_year;
                        weeks[week_of_year] = [];
                    }
                    weeks[week_of_year].push(this.days[index]);
                    i++;
                }

                return weeks;
            }
        },

        methods: {
            createByClick(date) {
                var now = new Date,
                    start_at = new Date(date),
                    mins_rounded = (((now.getMinutes() + 7.5)/15 | 0) * 15) % 60,
                    hours_rounded = ((((now.getMinutes()/105) + .5) | 0) + now.getHours()) % 24;

                start_at.setHours(hours_rounded);
                start_at.setMinutes(mins_rounded);

                console.log('Create Event at: ' + start_at.toISOString());
                this.$emit('creating', start_at.toISOString());
            },
            edit(todo) {
                this.$emit('updating', {
                    todo
                });
            },
        },

    };
</script>

<style scoped>
    .title {
        width: 100%;
        text-align: center;
        min-height: none;
    }

    .title > .day {
        font-size: 12px;
        font-weight: 500;
        letter-spacing: .3px;
        float: none;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
        width: max-content;
        min-width: 24px;
        color: #70757a;
        margin-top: 8px;
        line-height: 16px;
    }

    .title > .day:hover {
        text-decoration: none;
        margin-top: 3px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        background-color: #f1f3f4;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }

    .title.today > .day {
        background-color: #1a73e8 !important;
        margin-left: 0;
        text-decoration: none;
        margin-top: 3px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        background-color: #f1f3f4;
        -moz-border-radius: 50%;
        border-radius: 50%;
        color: #fff;
    }

    .title.today > .day:hover {
        background-color: #1967d2 !important;
    }

    .border-transparent {
        border-color: transparent !important;
    }
</style>