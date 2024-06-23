<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">{{ title }}</h1>
        <div v-if="!trip.id" class="mt-8 flex justify-center">
            <Loader />
        </div>
        <div v-else>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap :zoom="14" :center="trip.destination" ref="gMap"
                            style="width:100%; height: 256px;"></GMapMap>
                    </div>
                    <div class="mt-2">
                        <p class="text-xl">Going to <strong>{{ trip.destination_name }}</strong></p>
                    </div>
                </div>
                <div class="flex justify-between bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Decline</button>
                    <button class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none">Accept</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

import Loader from '@/components/Loader.vue'
import { onMounted } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { useTripStore } from '@/stores/trip'




const title = ref('Waiting for ride request....')
const trip = useTripStore()


onMounted(() => {

    // Assign Pusher to window
    window.Pusher = Pusher;
    
    let echo = new Echo({
        broadcaster: 'pusher',
        key: '131a5f51e691a523e3a6',
        cluster: 'ap1',
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    })


    // Debugging: Log connection state changes
    echo.connector.pusher.connection.bind('state_change', (states) => {
        console.log('Connection state changed:', states);
    });

    // Debugging: Log errors
    echo.connector.pusher.connection.bind('error', (err) => {
        console.error('Pusher error:', err);
    });

    echo.channel('drivers')
        .listen('TripCreated', (e) => {
            title.value = 'Ride requested:'
   
            trip.$patch(e.trip)
            console.log('TripCreated', e)
        })

})


</script>