<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Waiting for ride request...</h1>
        <div class="mt-8 flex justify-center">
            <Loader />
        </div>
    </div>
</template>


<script setup>

import Loader from '@/components/Loader.vue'
import { onMounted } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

onMounted(() => {

    // Assign Pusher to window
    window.Pusher = Pusher;
    
    let echo = new Echo({
        broadcaster: 'pusher',
        key: '131a5f51e691a523e3a6',
        cluster: 'ap1',
        forceTls: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    })

    // Debugging: Log connection state changes
    echo.connector.pusher.connection.bind('state_change', (states) => {
        console.log(states);
    });

    // Debugging: Log errors
    echo.connector.pusher.connection.bind('error', (err) => {
        console.error('Pusher error:', err);
    });

    echo.channel('drivers')
        .listen('TripCreated', (e) => {
            console.log('TripCreated', e)
        })

})


</script>