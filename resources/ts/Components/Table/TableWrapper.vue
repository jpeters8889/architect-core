<template>
  <div
    class="m-2 xl:m-4 overflow-x-scroll border border-gray-300 rounded shadow"
    scroll-region
  >
    <table class="w-full">
      <thead v-if="$slots.header">
        <slot name="header" />
      </thead>
      <tbody class="divide-y divide-gray-300">
        <slot />
      </tbody>
    </table>
  </div>
</template>
