<template>

    <div v-if="this.attend_status === 0" style="width:230px; background: #C9B92E" id="calling_staff">
        <img :src="'/system/gattuki/jftweb/img/calling_staff.png'" width="60px">
        <h3 style="color: white;margin-top: 10px">呼び出し中</h3>
    </div>
    <div v-else-if="this.attend_status === 1" style="width: 230px;" id="calling_staff">
        <img :src="'/system/gattuki/jftweb/img/call_staff.png'" width="60px" style="margin-top: 10px;">
        <h3 style="margin-top: 10px">呼出し</h3>
    </div>

</template>

<script>
    export default {
        props: ['table_id', 'order_id', 'calling_time'],
        data: function(){
            return {
                attend: 0,
                attend_status: 0,
                orderid: 0,
                tableid: 0,
            }
        },
        mounted() {
            this.orderid = this.order_id;
            this.tableid = this.table_id;
            if(this.calling_time != "") {
                this.attend_status = 0;
            }
            else {
                this.attend_status = 1;
            }

            // console.dir(this.attend_status);
        },
        created() {
            this.get_attend();
            this.get_self();
        },
        methods: {
            get_attend() {
                Echo.channel('attend-channel')//public channel
                .listen('AttendEvent', (event) => {
                    this.attend = event.attend_status[0];
                    console.dir(this.attend);
                    console.dir(this.orderid);

                    // location.href = window.location.href;
                    if(this.attend == this.orderid)
                    {
                        // this.attend_status = 1;
                        // location.href = window.location.href;

                        this.attend_status = 1;

                        // document.getElementById("calling_staff").style.background = "";
                        // document.getElementById("calling_staff").innerHTML = "<img src=\"/img/call_staff.png\" width=\"60px\" style=\"margin-top: 10px;\">\n" +
                        //     "        <h3>CALL STAFF</h3>";
                    }

                });
            },
            get_self() {
                Echo.channel('self-channel')//public channel
                .listen('SelfEvent', (event) => {
                    console.dir(event.order_id + '>>>' + event.calling_time);
                    if(this.orderid == event.order_id) {
                        if(event.calling_time != null)
                            this.attend_status = 0;
                        else
                            this.attend_status = 1;
                    }
                });
            },
        }
    };
</script>
