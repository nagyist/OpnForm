<template>
  <input-wrapper v-bind="inputWrapperProps">
    <template #label>
      <slot name="label" />
    </template>

    <textarea
      :id="id ? id : name"
      v-model="compVal"
      :disabled="disabled ? true : null"
      :class="[
        theme.default.input,
        theme.default.borderRadius,
        theme.default.spacing.horizontal,
        theme.default.spacing.vertical,
        theme.default.fontSize,
        {
          '!ring-red-500 !ring-2 !border-transparent': hasError,
          '!cursor-not-allowed !bg-neutral-200 dark:!bg-neutral-800': disabled,
        },
      ]"
      class="resize-y block"
      :name="name"
      :style="inputStyle"
      :placeholder="placeholder"
      :maxlength="maxCharLimit"
    />

    <template
      v-if="$slots.help"
      #help
    >
      <slot name="help" />
    </template>

    <template
      v-if="maxCharLimit && showCharLimit"
      #bottom_after_help
    >
      <small :class="theme.default.help">
        {{ charCount }}/{{ maxCharLimit }}
      </small>
    </template>

    <template
      v-if="$slots.error"
      #error
    >
      <slot name="error" />
    </template>
  </input-wrapper>
</template>

<script>
import {inputProps, useFormInput} from "../useFormInput.js"

export default {
  name: "TextAreaInput",
  components: {},
  mixins: [],

  props: {
    ...inputProps,
    maxCharLimit: {type: Number, required: false, default: null},
  },

  setup(props, context) {
    return {
      ...useFormInput(props, context),
    }
  },

  computed: {
    charCount() {
      return this.compVal ? this.compVal.length : 0
    },
  },
}
</script>
