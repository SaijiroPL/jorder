<template>

</template>

<script>
    export default {
        props: ['order_id'],
        data: function(){
            return {
                pay_status: ''
            }
        },
        mounted() {

        },
        created() {
            Echo.channel('pay-channel')//public channel
            .listen('PayEvent', (event) => {
                this.pay_status = event.pay_status;
                console.dir(this.pay_status);

                if(this.pay_status === 'pay_'+this.order_id) {
                    window.location.replace('../../');
                }
            });
        },
    };
</script>

<style scoped>

    .box-shadow {
        box-shadow: none;
    }
</style>