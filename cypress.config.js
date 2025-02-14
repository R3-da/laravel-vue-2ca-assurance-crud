import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
    baseUrl: 'http://localhost:8000', // Update this to match your development server URL
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
