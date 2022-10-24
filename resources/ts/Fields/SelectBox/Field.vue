<template>
  <Listbox
    v-slot="{ open }"
    v-model="newValue"
  >
    <div
      class="border transition duration-300 relative"
      :class="[
        open ? 'rounded-t' : 'rounded',
        error ? 'border-red-500' : 'border-gray-300 focus-within:border-gray-500'
      ]"
    >
      <ListboxButton class="relative w-full cursor-default py-2 pl-3 pr-10 text-left focus:outline-none sm:text-sm">
        <span class="block truncate">{{ selectValue }}</span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
        </span>
      </ListboxButton>

      <transition
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions class="absolute z-50 max-h-60 w-full overflow-auto rounded-b-md bg-white py-1 text-base shadow-lg focus:outline-none sm:text-sm border border-gray-500">
          <ListboxOption
            v-for="option in meta.options"
            v-slot="{ active, selected }"
            :key="option.key"
            :value="option.value"
            as="template"
          >
            <li :class="[active ? 'bg-gray-200 text-gray-800' : 'text-gray-600', 'relative cursor-default select-none py-2 px-4']">
              <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">
                {{ option.value }}
              </span>

              <span
                v-if="selected"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-800"
              >
                <CheckIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>

  <span
    v-if="error"
    class="text-sm font-semibold text-red-500"
    v-text="error"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {
  Listbox,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/24/outline';
import BlueprintFormFieldComponent from '../../Common/BlueprintFormFieldComponent';
import { SelectBoxOption } from '../../types';

export default defineComponent({

  components: {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    CheckIcon,
    ChevronUpDownIcon,
  },

  extends: BlueprintFormFieldComponent,

  props: {
    meta: {
      required: true,
      type: Object as () => { options: SelectBoxOption[] },
    },
  },

  computed: {
    selectValue(): string {
      const option = this.meta.options.filter((opt) => opt.key === this.newValue);

      if (option.length) {
        return option[0].value;
      }

      return 'Please Select';
    },
  },
});
</script>
