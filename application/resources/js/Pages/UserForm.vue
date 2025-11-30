<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import { IUser } from '../Shared/types';
import { computed } from 'vue';

type Props = {
  title: string,  
  user?: IUser,
  errors: Record<string, string>,
}

const props = defineProps<Props>();

const pageTitle = computed(() =>  props.user?.id ? `${props.title} #${props.user.id}` : props.title)

const form = useForm({
    first_name: props.user?.first_name ?? "",
    last_name: props.user?.last_name ?? "",
    email: props.user?.email ?? "",
    address: {
      country: props.user?.address?.country ?? "",
      city: props.user?.address?.city ?? "",
      address: props.user?.address?.address ?? "",
      post_code: props.user?.address?.post_code ?? "",
    },
});

function saveUser() {
  if (props.user) {
    form.put(`/users/${props.user?.id}`)
  } else {
    form.post(`/users`)
  }
}
</script>

<template>
<div class="p-6 bg-white mx-auto rounded-lg shadow-md max-w-[1600px]">
  <h1 class="text-2xl font-bold mb-4">{{ pageTitle }}</h1>

  <form @submit.prevent="saveUser">
    <div class="space-y-12">
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
          <label for="first-name" class="block text-sm/6 font-medium">First name</label>
          <input v-model="form.first_name" type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors?.first_name" class="text-red-500">{{ errors?.first_name }}</p>
        </div>

        <div class="sm:col-span-3">
          <label for="last-name" class="block text-sm/6 font-medium">Last name</label>
          <input v-model="form.last_name" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors?.last_name" class="text-red-500">{{ errors?.last_name }}</p>
        </div>

        <div class="sm:col-span-4">
          <label for="email" class="block text-sm/6 font-medium">Email address</label>
          <input v-model="form.email" id="email" name="email" type="email" autocomplete="email" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors?.email" class="text-red-500">{{ errors?.email }}</p>
        </div>

        <div class="sm:col-span-2 sm:col-start-1">
          <label for="city" class="block text-sm/6 font-medium">Country</label>
          <input v-model="form.address.country" type="text" name="city" id="city" autocomplete="address-level2" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors['address.country']" class="text-red-500">{{ errors['address.country'] }}</p>
        </div>

        <div class="sm:col-span-2">
          <label for="region" class="block text-sm/6 font-medium">City</label>
          <input v-model="form.address.city" type="text" name="region" id="region" autocomplete="address-level1" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors['address.city']" class="text-red-500">{{ errors['address.city'] }}</p>
        </div>

        <div class="sm:col-span-2">
          <label for="postal-code" class="block text-sm/6 font-medium">ZIP / Postal code</label>
          <input v-model="form.address.post_code" type="text" name="postal-code" id="postal-code" autocomplete="postal-code" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors['address.post_code']" class="text-red-500">{{ errors['address.post_code'] }}</p>
        </div>

        <div class="col-span-full">
          <label for="street-address" class="block text-sm/6 font-medium">Street address</label>
          <input v-model="form.address.address" type="text" name="street-address" id="street-address" autocomplete="street-address" class="block w-full rounded-md bg-gray/5 mt-2 px-3 py-1.5 text-base outline-1 -outline-offset-1 outline-gray/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
          <p v-if="errors['address.address']" class="text-red-500">{{ errors['address.address'] }}</p>
        </div>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-2">
      <Link href="/users" class="text-sm/6 font-semibold px-4 py-2 rounded-md hover:bg-gray-200 cursor-pointer">Cancel</Link>
      <button type="submit" class="rounded-md bg-gray-800 text-white text-sm font-bold px-4 py-2 rounded-md hover:bg-gray-700 cursor-pointer">Save</button>
    </div>
  </form>
</div>
</template>