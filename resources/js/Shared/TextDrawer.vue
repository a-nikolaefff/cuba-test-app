<template>
    <div id="textDrawer"
         class="w-full md:w-1/2 fixed z-40 h-screen p-4 overflow-y-auto bg-white"
         tabindex="-1">
        <h5 class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                      clip-rule="evenodd">
                </path>
            </svg>
            {{ title }}
        </h5>
        <button @click="hide"
                type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg
                text-sm w-8 h-8 absolute top-2.5 right-2.5 inline-flex items-center justify-center">
            <svg class="w-5 md:w-3 h-5 md:h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>

        <div v-if="isLoading" class="flex justify-center items-center h-full">
            <Loader>
                {{ loaderText }}
            </Loader>
        </div>

        <div v-if="error" class="flex justify-center items-center h-full uppercase tracking-wider">
            <p class="text-lg text-red-600">
                {{ error }}
            </p>
        </div>

        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            {{ text }}
        </p>
    </div>
</template>

<script>
import {onMounted} from "vue";
import {Drawer} from "flowbite";
import Loader from "./Loader.vue";

export default {
    components: {
        Loader
    },
    props: {
        title: String,
        text: String,
        isLoading: Boolean,
        loaderText: String,
        error: String,
    },

    setup() {
        let drawer = null;

        onMounted(() => {
            const drawerElement = document.getElementById('textDrawer');
            const options = {
                placement: 'right',
                backdrop: true,
                bodyScrolling: false,
                edge: false,
                edgeOffset: '',
                backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30',
            };
            drawer = new Drawer(drawerElement, options);
            drawer.hide();
        });

            function show() {
                drawer.show();
            }

            function hide() {
                drawer.hide();
            }

            return { show, hide }
    },
}
</script>
