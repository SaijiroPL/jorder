<template>

</template>

<script>
    export default {
        props: ['group_id'],
        data: function(){
            return {
                
            }
        },
        mounted() {

        },
        created() {
            Echo.channel('kitchen-dish-ready-channel')//public channel
            .listen('KitchenDishReadyEvent', (event) => {
                console.dir(this.group_id + '........' + event.group_id);
                if(this.group_id === event.group_id)
                    window.location.replace('../../kitchen/main_screen?group_id=' + event.group_id);
            });
        },
    };
</script>
