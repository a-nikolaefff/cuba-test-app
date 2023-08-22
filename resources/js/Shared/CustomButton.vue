<template>
    <button
        :class="[$attrs.class, 'py-2 px-4 text-white font-medium uppercase text-sm tracking-wider\n'+
'        border border-transparent shadow-sm rounded-md\n'+
'        bg-sky-600 hover:bg-sky-700 transition ease-out duration-300']"
    >
        <slot/>
    </button>
</template>

<script>
export default {
}
</script>


