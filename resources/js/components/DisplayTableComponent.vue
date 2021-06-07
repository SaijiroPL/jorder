<template>
    <h2>{{ this.display_table_name }}</h2>
</template>

<script>
    export default {
        props: ['order_id', 'table_name'],
        data: function(){
            return {
                display_table_name: ''
            }
        },
        mounted() {
            this.display_table_name = this.table_name;
        },
        created() {
            this.get_change_table();
        },
        methods: {
            get_change_table() {
                Echo.channel('table-move-channel')//public channel
                .listen('TableMoveEvent', (event) => {
                    if(this.order_id === event.display_table_name.order_id) {
                        location.href = window.location.href;
                    }
                });
            },
        }
    };
</script>

