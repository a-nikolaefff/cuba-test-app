<template>
    <input
        :placeholder="placeholder"
        type="text"
        autocomplete="off"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="[$attrs.class, 'p-2 shadow-sm border-2 border-gray-100 rounded-md']"
    />
</template>

<script>
export default {
    props: {
        modelValue: String,
        placeholder: String
    }
}
</script>
