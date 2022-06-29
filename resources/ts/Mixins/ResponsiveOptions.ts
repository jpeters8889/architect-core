import { defineComponent } from 'vue';
import { Breakpoint, ScreenBreakpoints, ScreenSize } from '../types';

export default defineComponent({
  computed: {
    breakpoints(): ScreenBreakpoints {
      return {
        mobile: { from: 0, to: 639 },
        sm: { from: 640, to: 767 },
        md: { from: 768, to: 1023 },
        lg: { from: 1024, to: 1279 },
        xl: { from: 1280, to: 1535 },
        '2xl': { from: 1536, to: 9999 },
      };
    },
  },

  methods: {
    currentSize(): ScreenSize {
      let currentSize: ScreenSize = 'mobile';
      let currentBreakpoint: Breakpoint = {} as Breakpoint;

      Object.keys(this.breakpoints).forEach((breakpoint: string | ScreenSize) => {
        currentBreakpoint = this.breakpoints[breakpoint as ScreenSize];

        if (window.innerWidth >= currentBreakpoint.from && window.innerWidth < currentBreakpoint.to) {
          currentSize = breakpoint as ScreenSize;
        }
      });

      return currentSize;
    },

    isLte(breakpoint: ScreenSize): boolean {
      if (!Object.keys(this.breakpoints).includes(breakpoint)) {
        return true;
      }

      return window.innerWidth <= this.breakpoints[breakpoint].from;
    },

    isLt(breakpoint: ScreenSize): boolean {
      if (!Object.keys(this.breakpoints).includes(breakpoint)) {
        return true;
      }

      return window.innerWidth < this.breakpoints[breakpoint].from;
    },

    is(breakpoint: ScreenSize): boolean {
      if (!Object.keys(this.breakpoints).includes(breakpoint)) {
        return true;
      }

      const sizes = this.breakpoints[breakpoint];

      return window.innerWidth >= sizes.from && window.innerWidth < sizes.to;
    },

    isGt(breakpoint: ScreenSize): boolean {
      if (!Object.keys(this.breakpoints).includes(breakpoint)) {
        return true;
      }

      return window.innerWidth > this.breakpoints[breakpoint].from;
    },

    isGte(breakpoint: ScreenSize): boolean {
      if (!Object.keys(this.breakpoints).includes(breakpoint)) {
        return true;
      }

      return window.innerWidth >= this.breakpoints[breakpoint].from;
    },
  },
});
