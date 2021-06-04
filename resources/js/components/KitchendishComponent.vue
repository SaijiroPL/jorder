<template>
    <div v-if="group_order_dishes.length > 0">
    <!--<table v-for="(group_order_dish, key) in group_order_dishes.slice().reverse()">-->
    <table v-for="(group_order_dish, key) in group_order_dishes" :key="group_order_dish.id">
        <!--<tr v-if="group_order_dish.ready_flag === 0">-->
        <tr :id="'tr_group_order_dish_'+group_order_dish.id">
            <td width="7%" align="center">
                <div :id="'time_' + key + '_' + group" v-if="group_order_dish.time >= 0 && group_order_dish.time < 10 && group_order_dish.ready_flag !== 1">
                    <span class="circle_middle">
                        <p class="data green">{{ group_order_dish.time }}</p>
                    </span>
                </div>
                <div :id="'time_' + key + '_' + group" v-if="group_order_dish.time >= 10 && group_order_dish.time < 15 && group_order_dish.ready_flag !== 1">
                    <span class="circle_middle">
                        <p class="data yellow">{{ group_order_dish.time }}</p>
                    </span>
                </div>
                <div :id="'time_' + key + '_' + group" v-if="group_order_dish.time >= 15 && group_order_dish.time < 20 && group_order_dish.ready_flag !== 1">
                    <span class="circle_middle">
                        <p class="data red">{{ group_order_dish.time }}</p>
                    </span>
                </div>
                <div :id="'time_' + key + '_' + group" v-if="group_order_dish.time >= 20 && group_order_dish.ready_flag !== 1">
                    <span class="circle_middle">
                        <p class="data blink">{{ group_order_dish.time }}</p>
                    </span>
                </div>
                <div :id="'time_' + key + '_' + group" v-if="group_order_dish.ready_flag === 1">
                    <span class="circle_middle">
                        <p class="data grey">{{ group_order_dish.time }}</p>
                    </span>
                </div>
            </td>
            <!--<td width="13%" style="padding-left: 2px;padding-right: 2px;" v-on:click="extract_table_number(group_order_dish.display_table_id)">-->
            <td width="13%" style="padding-left: 2px;padding-right: 2px;" class="table_list" :data-id = group_order_dish.order_id >
                <b>{{ group_order_dish.display_table }}</b><br>({{ group_order_dish.table_count }})
            </td>
            <td width="8%">
                <img v-if="group_order_dish.dish_image !== null" :src="'/system/gattuki/jftweb/dishes/' + group_order_dish.dish_image" class="general">
            </td>
            <td width="56%" class="dish_list" :data-id=group_order_dish.dish_id>
                <div><b>{{ group_order_dish.dish_name }}</b></div>
                <div v-for="option in group_order_dish.options" :key="option.id">
                {{ option.option_name }}: <b>{{ option.item_name }}</b>&nbsp;
                </div>
            </td>
            <td width="8%">
                <span class="multiple">&times;</span>&nbsp;
                <span class="qty">{{ group_order_dish.count }}</span>
            </td>
            <td width="8%">
                <label class="checkbox_container">
                    <input v-if="group_order_dish.ready_flag === 1 || chk_group_order_dishes[group_order_dish.id] == 1" :id="'chk_group_order_dish_'+group_order_dish.id" :disabled="isReady" type="checkbox" checked="checked">
                    <input v-else type="checkbox" :id="'chk_group_order_dish_'+group_order_dish.id" :disabled="isReady">
                    <span class="checkboxmark" v-on:click="ready(group_order_dish.id, key)"></span>
                </label>
            </td>
        </tr>
    </table>

    </div>
</template>

<script>
    export default {
        props: ['group'],
        data: function(){
            return {
                group_order_dishes: [],
                chk_group_order_dishes: [],
                isReady: false,
            }
        },
        mounted() {
            setTimeout(this.myTimer, 1000);
        },
        created() {
            Echo.channel('kitchen-channel')//public channel
            .listen('KitchenEvent', (event) => {

                console.log(this.group + "=>" + event.added_dish.group_id);
                var g_id = event.added_dish.group_id;

                if(('[' + event.added_dish.group_id + ']').includes('&' + this.group + '&') == true) {
                    this.group_order_dishes.push({

                        display_table: event.added_dish.display_table,
                        table_count: event.added_dish.table_count,
                        dish_image: event.added_dish.dish_image,
                        dish_name: event.added_dish.dish_name,
                        options: event.added_dish.options,
                        count: event.added_dish.count,
                        ready_flag: event.added_dish.ready_flag,
                        starting_time: event.added_dish.starting_time,
                        calling_time: event.added_dish.calling_time,
                        dish_id: event.added_dish.dish_id,
                        dish_price: event.added_dish.dish_price,
                        display_table_id: event.added_dish.display_table_id,
                        group_id: event.added_dish.group_id,
                        id: event.added_dish.id,
                        order_id: event.added_dish.order_id,
                        total_price: event.added_dish.total_price
                    });
                    this.chk_group_order_dishes[event.added_dish.id] = 0;
                    // this.group_order_dishes.reverse();//display array reverse

                    location.href = window.location.href;
                }
            });

            // display part for dish list by group change
            this.get_by_group_order_dishes(this.group);

            Echo.channel('changecount-channel')//public channel
            .listen('ChangeCountEvent', (event) => {
                    // console.dir(event.group_id);
                if(('[' + event.group_id + ']').includes('&' + this.group + '&') == true) {
                    location.href = window.location.href;
                }
            });

        },
        methods: {
            // api for get dish list by change group
            get_by_group_order_dishes(group_id) {
                console.log(group_id);
                axios.get('/system/gattuki/jftweb/api/get_change_group_dish/' + group_id)
                .then(response => {
                    this.group_order_dishes = response.data;
                })
            },
            //ready flag check part
            ready(id, key) {
                var selected_id = id;
                var group_id = this.group;

                if (this.isReady) {
                    return;
                }
                this.isReady = true;
                var d = new Date();
                const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                const mo = new Intl.DateTimeFormat('en', { month: '2-digit' }).format(d);
                const da = new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d);
                const h = new Intl.DateTimeFormat('en', { hour12: false, hour: '2-digit', minute: '2-digit',second: '2-digit' }).format(d);

                this.group_order_dishes = this.group_order_dishes.map((item) => {
                    if (item.id === id) {
                        const newReadyTime = `${ye}-${mo}-${da} ${h}`;
                        return {
                            ...item,
                            ready_flag: item.ready_flag === 1 ? 0 : 1,
                            ready_time: item.ready_flag === 1 ? '' : newReadyTime
                        }
                    } else return item
                })

                axios.post('/system/gattuki/jftweb/api/ready', {selected_id: selected_id, group_id: group_id})
                .then(response => {
                    this.isReady = false;
                })
            },
            myTimer() {
                var red_count = 0;
                var yellow_count = 0;
                var green_count = 0;

                this.group_order_dishes = this.group_order_dishes.map((item) => {
                    console.log(item, 'item');
                    var current_time = item.ready_flag === 1 ? this.parseDate(item.ready_time) : new Date();
                    var created_time = this.parseDate(item.created_at);
                    var elapsed_time = (current_time.getTime() - created_time.getTime()) / 60000;
                    if (elapsed_time >=0 && elapsed_time < 10) {
                        green_count++;
                    } else if (elapsed_time >=10 && elapsed_time < 20) {
                        yellow_count++;
                    } else {
                        red_count++;
                    }
                    item.time = Math.round(elapsed_time);
                    return item;
                    // return {...item, time: Math.round(elapsed_time)};
                })
                document.getElementById("red_count").innerText = red_count;
                document.getElementById("yellow_count").innerText = yellow_count;
                document.getElementById("green_count").innerText = green_count;
                document.getElementById("total_count").innerText = red_count + yellow_count + green_count + ' / ';
                setTimeout(this.myTimer, 1000);

                axios.post('/system/gattuki/jftweb/api/unreadyCount', {group_id: this.group}).then(
                    response => {
                        document.getElementById('miss_count').innerText = response.data.total + ' / ' + response.data.group
                    }
                )
            },

            parseDate(str) {
                var dt = str.split(' ')[0];
                var year = dt.split('-')[0];
                var month = dt.split('-')[1];
                var day = dt.split('-')[2];

                var tm = str.split(' ')[1];
                var hours = tm.split(':')[0];
                var minutes = tm.split(':')[1];

                return new Date(year, month - 1, day, hours, minutes);
            }
        }
    };
</script>
