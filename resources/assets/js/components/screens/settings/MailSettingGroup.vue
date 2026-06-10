<template>
  <form @submit.prevent="save">
    <SettingGroup>
      <template #title>Mail (SMTP) Configuration</template>
      <FormRow>
        <template #help>
          <span id="mailSettingsHelp">
            Configure your SMTP settings to allow Koel to send emails. This is essential for
            features like password resets, user invitations, and notifications.
          </span>
        </template>

        <div class="space-y-4 md:w-2/3" aria-describedby="mailSettingsHelp">
          <div>
            <label class="block text-sm font-medium mb-1">Host</label>
            <TextInput
              v-model="form.host"
              required
              name="mail_host"
              placeholder="smtp.mailgun.org"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Port</label>
            <TextInput
              v-model="form.port"
              type="number"
              required
              name="mail_port"
              placeholder="2525"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Username</label>
            <TextInput v-model="form.username" name="mail_username" placeholder="Mail username" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <TextInput
              v-model="form.password"
              type="password"
              name="mail_password"
              placeholder="Mail password"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Encryption</label>
            <TextInput
              v-model="form.encryption"
              name="mail_encryption"
              placeholder="tls, ssl, or null"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">From Address</label>
            <TextInput
              v-model="form.from_address"
              type="email"
              required
              name="mail_from_address"
              placeholder="koel@example.com"
            />
          </div>
        </div>
      </FormRow>

      <template #footer>
        <Btn data-testid="submit" type="submit">Save Mail Settings</Btn>
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
  host: "",
  port: "2525",
  username: "",
  password: "",
  encryption: "tls",
  from_address: "",
});

const save = async () => {
  showOverlay({ message: "Saving Mail Settings…" });

  try {
    await http.put("settings/mail", form);
    toastSuccess("Mail settings updated successfully.");
  } catch (error: unknown) {
    useErrorHandler("dialog").handleHttpError(error);
  } finally {
    hideOverlay();
  }
};
</script>
