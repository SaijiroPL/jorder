<template>
    <ul class="nav navbar pt-0 pb-0 mt-0 mb-0 box-shadow">
        <li class="menu bg-green" id="ready_to_pay"><img :src="'/img/dollar.png'" class="noti_img" />{{ count_notification.ready_pay_count }}</li>
        <li class="menu bg-pinq" id="calling_count"><img :src="'/img/notify.png'"  class="noti_img" />{{ count_notification.attend_count }}</li>
        <li class="menu bg-yellow" id="review_count"><img :src="'/img/chat.png'"  class="noti_img" />{{ count_notification.review_count }}</li>
        <li class="menu bg-info" id="note_count"><img :src="'/img/writechat.png'"  class="noti_img" />{{ count_notification.note_count }}</li>
    </ul>
</template>

<script>
    export default {
        // props: ['count_notification'],
        data: function(){
            return {
                count_notification: [],
                soundUrl: 'http://soundbible.com/mp3/analog-watch-alarm_daniel-simion.mp3',
            }
        },
        mounted() {
            axios.get('/api/CountNotification')
            .then(response => {
                this.count_notification = response.data;
            })

        },
        created() {
            Echo.channel('notification-channel')//public channel
            .listen('NotificationEvent', (event) => {
                var audio = new Audio(this.soundurl);
                audio.play();
                this.count_notification = event.count_notification;
                if( this.count_notification.selected == 1 ){
                    document.getElementById("belled_icon_"+this.count_notification.table_id).src = "/img/calling.png";
                    document.getElementById("belled_list_"+this.count_notification.table_id).src = "/img/calling.png";
                }
                else if( this.count_notification.selected == 2 ) {
                    document.getElementById("call_icon_"+this.count_notification.table_id).src = "/img/alarm.png";
                    document.getElementById("call_list_"+this.count_notification.table_id).src = "/img/alarm.png";
                }
                else if( this.count_notification.selected == 3 ) {
                    document.getElementById("call_icon_"+this.count_notification.table_id).src = "";
                    document.getElementById("call_list_"+this.count_notification.table_id).src = "";
                    document.getElementById("call_list_"+this.count_notification.table_id).style = "";
                }
                else if( this.count_notification.selected == 4 ) {
                    document.getElementById("review_icon_"+this.count_notification.table_id).src = "/img/msg.png";
                    document.getElementById("review_list_"+this.count_notification.table_id).src = "/img/msg.png";
                }

            });
        },
    };
</script>

<style scoped>

    .box-shadow {
        box-shadow: none;
    }

    .noti_img {
        width: 45px;
    }
</style>
