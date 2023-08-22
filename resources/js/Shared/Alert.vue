<template>
    <div class="px-6 py-2 font-light rounded-lg"
         :class="{'text-sky-700 bg-sky-50' : type === 'success', 'text-red-700 bg-red-50' : type === 'error'}"
         role="alert">
        <slot/>
    </div>
</template>

<script>
export default {
    props: {
        type: String,
    }
}
</script>


