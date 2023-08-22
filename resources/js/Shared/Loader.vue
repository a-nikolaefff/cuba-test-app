<template>
    <div class="flex justify-center items-center">
                <span class="align-middle relative flex h-6 w-6">
        <span
            class="align-middle animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-600 opacity-75">
        </span>
        <span class="align-middle relative inline-flex rounded-full h-6 w-6 bg-sky-600"></span>
    </span>
        <span class="ml-4 tex-xs text-sky-600 uppercase tracking-wider">
            <slot/>
        </span>
    </div>
</template>

<script>
export default {
}
</script>
