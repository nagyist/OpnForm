<template>
  <div
    class="p-1 rounded-md flex items-center justify-center"
    :class="[bgClass, textClass]"
  >
    <Icon
      :name="icon"
      class="h-4 w-4"
    />
  </div>
</template>

<script setup>
import blocksTypes from '~/data/blocks_types.json'

const props = defineProps({
  type: {
    type: String,
    required: true
  },
  bgClass: {
    type: String,
    default: ''
  },
  textClass: {
    type: String,
    default: ''
  }
})

const bgClass = computed(() => props.bgClass || blocksTypes[props.type]?.bg_class || '')
const textClass = computed(() => props.textClass || blocksTypes[props.type]?.text_class || '')
const icon = computed(() => blocksTypes[props.type]?.icon || '')
</script>