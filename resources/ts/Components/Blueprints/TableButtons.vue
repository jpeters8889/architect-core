<template>
  <div class="flex space-x-1">
    <div
      v-if="settings.canDuplicate"
      title="Duplicate"
      @click="handleButtonPress('duplicate')"
    >
      <DuplicateIcon class="w-4 h-4 hover:text-gray-900 transition cursor-pointer" />
    </div>
    <div
      v-if="settings.publicUrl"
      title="Open"
    >
      <EyeIcon class="w-4 h-4 hover:text-gray-900 transition cursor-pointer" />
    </div>
    <div
      v-if="settings.canEdit"
      title="Edit"
      @click="handleButtonPress('edit')"
    >
      <PencilAltIcon class="w-4 h-4 hover:text-gray-900 transition cursor-pointer" />
    </div>
    <template v-if="settings.canDelete">
      <div
        v-if="!settings.isDeleted"
        title="Delete"
        @click="handleButtonPress('delete')"
      >
        <TrashIcon class="w-4 h-4 hover:text-gray-900 transition cursor-pointer" />
      </div>

      <div
        v-else
        title="Restore"
        @click="handleButtonPress('restore')"
      >
        <FolderAddIcon class="w-4 h-4 hover:text-gray-900 transition cursor-pointer" />
      </div>
    </template>
  </div>
</template>

<script lang="ts">
import {
  DuplicateIcon, EyeIcon, PencilAltIcon, TrashIcon, FolderAddIcon,
} from '@heroicons/vue/outline';
import { defineComponent } from 'vue';
import { BlueprintTableButtonEvent, BlueprintTableButtonSettings } from '../../types';

export default defineComponent({
  components: {
    DuplicateIcon, EyeIcon, PencilAltIcon, TrashIcon, FolderAddIcon,
  },

  emits: ['buttonPress'],

  props: {
    settings: {
      required: true,
      type: Object as () => BlueprintTableButtonSettings,
    },
  },

  methods: {
    handleButtonPress(button: BlueprintTableButtonEvent) {
      this.$emit('buttonPress', button, this.settings.id);
    },
  },
});
</script>
