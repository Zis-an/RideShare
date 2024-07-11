<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">{{ title }}</h1>
        <div>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap :zoom="14" :center="location.current.geometry" ref="gMap" style="width: 100%; height: 256px;">
                            <GMapMarker :position="location.current.geometry" :icon="destinationIcon"/>
                        </GMapMap>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <span>{{ message }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useLocationStore } from '@/stores/location'
import { useTripStore } from '@/stores/trip'
import { ref, onMounted } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'


const location = useLocationStore()
const trip = useTripStore()

const title = ref('Waiting on a driver...')
const message = ref('When a driver accepts the trip, their info will appear here....')

const destinationIcon = {
    url: 'https://openmoji.org/data/color/svg/E242.svg',
    scaledSize: {
        width: 48,
        height: 48
    }
}

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



echo.channel(`passenger_${trip.user_id}`)
    .listen('TripAccepted', (e) => {
                
        trip.$patch(e.trip)
        
        console.log("Here is the trip: " + e.trip);

        title.value = "A driver is on the way!"
        
        message.value = `${e.trip.driver.user.name} is coming in a ${e.trip.driver.year} ${e.trip.driver.color} ${e.trip.driver.model} with a license plate #${e.trip.driver.license_plate}`
    })

})

</script>
