<template>
    <div v-if="count_notification.attend_count > 0">
        <img :src="'/img/calling_bell.png'" class="calling_bell" id="calling_bell">
    </div>
</template>

<script>
    export default {
        props: ['attend_status'],
        data: function(){
            return {
                count_notification: [],
                soundUrl: 'http://soundbible.com/mp3/analog-watch-alarm_daniel-simion.mp3',
            }
        },
        mounted() {
            this.get_notification();
            this.get_echo();
            // axios.get('CountNotification')
            //     .then(response => {
            //         this.count_notification = response.data;
            //     })
            // this.get_notification();
        },
        created() {
            this.get_notification();
            this.get_echo();
        },
        methods: {
            get_notification() {
                axios.get('/api/CountNotification')
                    .then(response => {
                        this.count_notification = response.data;
                    });
            },
            get_echo() {
                Echo.channel('notification-channel')//public channel
                    .listen('NotificationEvent', (event) => {
                        this.count_notification = event.count_notification;
                        console.dir('notification-channel');
                        if (this.count_notification.attend_count > 0) {
                            var audio = new Audio(this.soundurl);
                            audio.play();
                        }
                    });
            },
        }
    };
</script>
