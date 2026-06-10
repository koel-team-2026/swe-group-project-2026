<template>
  <form @submit.prevent="save">
    <SettingGroup>
      <template #title>Search Provider Configuration</template>
      <FormRow>
        <template #help>
          <span id="searchSettingsHelp">
            Select and configure a dedicated search engine driver to improve Koel's search speed and
            accuracy. Options include Database (default), Algolia, Elasticsearch, and Meilisearch.
          </span>
        </template>

        <div class="space-y-4 md:w-2/3" aria-describedby="searchSettingsHelp">
          <div>
            <label class="block text-sm font-medium mb-1">Scout Driver</label>
            <select
              v-model="form.driver"
              class="w-full rounded border-gray-300 text-gray-700 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 px-3 py-2 focus:ring focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="database">Database (Default)</option>
              <option value="algolia">Algolia</option>
              <option value="elasticsearch">Elasticsearch</option>
              <option value="meilisearch">Meilisearch</option>
              <option value="null">None</option>
            </select>
          </div>

          <div
            v-if="form.driver === 'algolia'"
            class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4"
          >
            <div>
              <label class="block text-sm font-medium mb-1">Algolia App ID</label>
              <TextInput v-model="form.algolia_app_id" required placeholder="Your Algolia App ID" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Algolia Secret</label>
              <TextInput
                v-model="form.algolia_secret"
                type="password"
                required
                placeholder="Your Algolia Secret Key"
              />
            </div>
          </div>

          <div
            v-if="form.driver === 'elasticsearch'"
            class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4"
          >
            <div>
              <label class="block text-sm font-medium mb-1">Elasticsearch Host</label>
              <TextInput v-model="form.elasticsearch_host" required placeholder="localhost:9200" />
            </div>
          </div>

          <div
            v-if="form.driver === 'meilisearch'"
            class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4"
          >
            <div>
              <label class="block text-sm font-medium mb-1">Meilisearch Host</label>
              <TextInput
                v-model="form.meilisearch_host"
                required
                placeholder="http://127.0.0.1:7700"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Meilisearch Key</label>
              <TextInput
                v-model="form.meilisearch_key"
                type="password"
                required
                placeholder="Your Meilisearch Key"
              />
            </div>
          </div>
        </div>
      </FormRow>

      <template #footer>
        <Btn data-testid="submit" type="submit">Save Search Settings</Btn>
      </template>
    </SettingGroup>
  </form>
</template>

<script lang="ts" setup>
import { reactive } from "vue";
import { http } from "@/services/http";
import { useMessageToaster } from "@/composables/useMessageToaster";
import { useOverlay } from "@/composables/useOverlay";
import { useErrorHandler } from "@/composables/useErrorHandler";

import Btn from "@/components/ui/form/Btn.vue";
import TextInput from "@/components/ui/form/TextInput.vue";
import FormRow from "@/components/ui/form/FormRow.vue";
import SettingGroup from "@/components/screens/settings/SettingGroup.vue";

const { toastSuccess } = useMessageToaster();
const { showOverlay, hideOverlay } = useOverlay();

const form = reactive({
  driver: "database",
  algolia_app_id: "",
  algolia_secret: "",
  elasticsearch_host: "localhost:9200",
  meilisearch_host: "http://127.0.0.1:7700",
  meilisearch_key: "",
});

const save = async () => {
  showOverlay({ message: "Saving Search Settings…" });

  try {
    await http.put("settings/search", form);
    toastSuccess("Search settings updated successfully.");
  } catch (error: unknown) {
    useErrorHandler("dialog").handleHttpError(error);
  } finally {
    hideOverlay();
  }
};
</script>
