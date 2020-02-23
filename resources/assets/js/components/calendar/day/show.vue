<template>
    <div class="col events" @click="createByClick">
        <div class="event shadow pointer" v-for="(event, index) in events" :style="{ top: event.position.top, height: event.position.height, left: event.position.left, width: event.position.width, 'border-left': '5px solid ' + (event.team ? event.team.hex_color_code : 'transparent'), }" @click.stop="edit(index, event)">
            <div class="d-flex justify-content-center align-tems-stretch flex-column px-3">
                <div>{{ event.name }} <span>{{ $parent.formatTime(event.start_at) }} - {{ $parent.formatTime(event.end_at) }}</span></div>
                <!-- <div class="resize-handle" @click.stop="" @mouseleave="isResizing = false" @mousedown="isResizing = true" @mouseup="isResizing = false" @mousemove="handleResize(index, $event)"></div> -->
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";

    export default {

        props: {
            day: {
                type: Object,
                required: true,
            },
            eventsRaw: {
                type: Array,
                required: true,
            },
        },

        computed: {
            events() {
                var events = [];
                for(var index in this.eventsRaw) {
                    var event = _.clone(this.eventsRaw[index], true),
                        start_at = new Date(event.start_at),
                        end_at = new Date(event.end_at),
                        start_at_industry = this.toIndustryHours(start_at),
                        end_at_industry = this.toIndustryHours(end_at),
                        startDate = start_at.toDateString(),
                        endDate = end_at.toDateString(),
                        thisDate = this.date.toDateString(),
                        isSameDay = (startDate == endDate),
                        isStartDay = (thisDate == startDate),
                        isEndDay = (thisDate == endDate);

                    event.start_at = start_at.toISOString();
                    event.end_at = end_at.toISOString();
                    event.overlap = {
                        count: 0,
                        position: 0,
                    };

                    event.position = {
                        top: ((!isSameDay && !isStartDay) ? 0 : (start_at_industry * 48)) + 1 + 'px',
                        height: (isSameDay ? ((end_at_industry - start_at_industry) * 48) : ((isStartDay ? (24 - start_at_industry) : (isEndDay ? (24 - (24 - end_at_industry)) : 24)) * 48)) -2 + 'px',
                        left: 'calc(15px + 0%)',
                        width: 'calc(100% - 30px)',
                    };
                    events.push(event);
                }

                events.sort(function(a, b) {
                    return a.start_at - b.start_at;
                });

                var groups = this.eventGroups(events);
                for (var i in groups) {
                    var group = groups[i],
                        overlap_count = group.length;

                    for (var index in group) {
                        events[group[index]].overlap.count = overlap_count;
                        events[group[index]].overlap.position = index;

                        var percent = 1/events[group[index]].overlap.count * 100;
                        events[group[index]].position.left = 'calc(15px + ' + (percent * events[group[index]].overlap.position) + '%)';
                        events[group[index]].position.width = 'calc(' + percent + '% - 30px)';
                    }

                }

                return events;
            },
        },

        data() {
            return {
                date: new Date(this.day.date),
                isResizing: false,
            };
        },

        methods: {
            edit(index, todo) {
                this.$emit('updating', {
                    index,
                    todo
                });
            },
            eventGroups(sortedEvents) {
                var groups = [];
                var a, b,
                    events_count = sortedEvents.length,
                    groupIndex = 0;

                for(var index = 0; index < events_count - 1;index++) {
                    a = sortedEvents[index];
                    b = sortedEvents[index + 1];

                    if ((a.start_at <= b.start_at && a.end_at > b.start_at) || (a.start < b.end_at && a.end_at >= b.end_at) ) {
                        if (! (groupIndex in groups)) {
                            groups[groupIndex] = [];
                        }
                        groups[groupIndex].push(index);
                        groups[groupIndex].push(index + 1);
                    }
                    else {
                        groupIndex++;
                        if (! (groupIndex in groups)) {
                            groups[groupIndex] = [];
                        }
                        groups[groupIndex].push(index + 1);
                    }
                }

                for (var index in groups) {
                    groups[index] = [...new Set(groups[index])];
                }

                return groups;
            },
            toIndustryHours(date) {
                var hours = date.getHours(),
                    mins = date.getMinutes(),
                    secs = date.getSeconds();

                return (hours + (mins/60) + (secs / 3600)).toFixed(2);
            },
            createByClick(event) {
                var start_at = this.pixelToDate(event.layerY);
                console.log('Create Event at: ' + start_at.toISOString());
                this.$emit('creating', start_at);
            },
            pixelToDate(pixel) {
                var date = this.date,
                    industryHours = pixel / 48,
                    hours = parseInt(industryHours),
                    mins = parseInt((industryHours - hours) * 60),
                    mins_rounded = (((mins + 7.5)/15 | 0) * 15) % 60,
                    hours_rounded = ((((mins/105) + .5) | 0) + hours) % 24;

                date.setHours(hours_rounded);
                date.setMinutes(mins_rounded);
                date.setSeconds(0);

                return date;
            },
            pixeltoSecond(pixel) {
                return pixel * 30;
            },
            updatingEndAt(index, seconds) {
                console.log('updatingEndAt: ' + seconds + ' seconds');
                this.$emit('updatingEndAt', {
                    index,
                    seconds,
                });
            },
            handleResize(index, event) {
                console.log(event);
                if (!this.isResizing) {
                    return;
                }
                console.log(this.pixeltoSecond(event.layerY));
                this.updatingEndAt(index, this.pixeltoSecond(event.layerY));
            },
        },
    };
</script>

<style scoped>
    .events {
        position: relative;
        border-left: #dadce0 1px solid;
    }

    .event {
        position: absolute;
        z-index: 4;
        min-height: 20px;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        overflow: hidden;
    }

    .event .resize-handle {
        background-color: red;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        max-height: 8px;
        display: -webkit-box;
        display: -moz-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5004;
        cursor: ns-resize;
    }
</style>