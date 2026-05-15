<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-6xl mx-auto space-y-8">
            <!-- Mouse Position -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Mouse Position</h2>
                <p>X: {{ mouse.x }} | Y: {{ mouse.y }}</p>
            </div>

            <!-- LocalStorage Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">LocalStorage Example</h2>
                <p>Stored Preference: {{ preference }}</p>
                <button @click="togglePreference" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-md">Toggle Preference</button>
            </div>

            <!-- Media Query Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Media Query Example</h2>
                <p>{{ isMobile ? 'Mobile' : 'Desktop' }} screen detected</p>
            </div>

            <!-- Fetch Data Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Fetch Data Example</h2>
                <div v-if="isLoading" class="text-lg text-gray-800">Loading...</div>
                <div v-if="!isLoading && data" class="text-lg text-gray-800">
                    <p>{{ data?.title }}</p>
                </div>
                <div v-if="error" class="text-lg text-red-600">
                    Error: {{ error.message }}
                </div>
            </div>

            <!-- Intersection Observer Example -->
            <div v-intersect="onIntersect" class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Intersection Observer Example</h2>
                <p>This section becomes visible when scrolled into view.</p>
            </div>

            <!-- Dark Mode Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Dark Mode Example</h2>
                <p>Dark Mode: {{ darkMode ? 'Enabled' : 'Disabled' }}</p>
                <button @click="toggleDarkMode" class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-md">
                    Toggle Dark Mode
                </button>
            </div>

            <!-- Window Size Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Window Size Example</h2>
                <p>Window Size: {{ windowSize.width }} x {{ windowSize.height }}</p>
            </div>

            <!-- Event Listener Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Event Listener Example</h2>
                <p>Key Pressed: {{ keyPressed }}</p>
            </div>

            <!-- Timeout Function Example -->
            <div class="bg-white shadow-xl rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Timeout Function Example</h2>
                <p>Timeout: {{ timeoutMessage }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    useMouse,
    useLocalStorage,
    useMediaQuery,
    useFetch,
    useIntersectionObserver,
    useDark,
    useWindowSize,
    useEventListener,
    useTimeoutFn,
} from '@vueuse/core';

// Mouse position example
const mouse = useMouse();

// LocalStorage example
// 使用 useLocalStorage 返回 ref
const preference = useLocalStorage('user-preference', 'light');

// 切换偏好设置
const togglePreference = () => {
    preference.value = preference.value === 'light' ? 'dark' : 'light';
};


// MediaQuery example
const isMobile = useMediaQuery('(max-width: 768px)');

// Fetch data example
const { data, error, isLoading } = useFetch('https://jsonplaceholder.typicode.com/posts/1').json();

// Intersection Observer example
const onIntersect = () => {
    console.log('Element is now in view!');
};

// Dark Mode example
const { isDark: darkMode, toggle: toggleDarkMode } = useDark();

// Window Size example
const windowSize = useWindowSize();

// Event Listener example
let keyPressed = '';
useEventListener('keydown', (e) => {
    keyPressed = e.key;
});

// Timeout Function example
const timeoutMessage = useTimeoutFn(() => {
    return 'Timeout has occurred!';
}, 3000); // Will trigger in 3 seconds after component is mounted
</script>

<style scoped>
/* Add custom styles if necessary */
</style>
