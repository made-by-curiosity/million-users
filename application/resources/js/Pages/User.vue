<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { IPaginatorLinks, IUser, IUserAddress } from '../Shared/types';
import { ref, watch } from 'vue';

type Props = {
    users: IUser[],
    total: number,
    currentPage: number,
    from: number | null,
    to: number | null,
    searchText: string,
    links: IPaginatorLinks[],
}

const props = defineProps<Props>();

const searchQuery = ref<string>(props.searchText)
const selectedUser = ref<IUser | null>(null)
const dialogRef = ref<HTMLDialogElement | null>(null)

const headersConfig = [
    {
        title: 'ID',
    },
    {
        title: 'First name',
    },
    {
        title: 'Last name',
    },
    {
        title: 'Email',
    },
    {
        title: 'Updated at',
    },
    {
        title: '', // action button empty title
    },
];

function getUserFullAddress(address: IUserAddress): string {
    const addressItems = [address.country, address.city, address.post_code, address.address];
    
    return addressItems.filter(item => !!item).join(', ');
}

function getUserCellData(user: IUser): Record<'data', any>[] {
    return [
        {
            data: user.id,
        },
        {
            data: user.first_name,
        },
        {
            data: user.last_name,
        },
        {
            data: user.email,
        },
        {
            data: user.updated_at,
        },
    ]
}

let timeout;
watch(searchQuery, (value) => {
    const searchText = value.trim();
    clearTimeout(timeout);
    timeout = setTimeout(() => {
    router.get('/users', searchText ? { search: searchText } : {}, { preserveState: true, replace: true });
    }, 300);
});

function showUserInfo(userId: number): void {
  selectedUser.value = props.users.find(user => user.id === userId) ?? null;
  dialogRef.value?.showModal()
}

function hideUserInfo(): void {
  selectedUser.value = null;
  dialogRef.value?.close()
}
</script>

<template>
  <div class="p-6 bg-white mx-auto rounded-lg shadow-md max-w-[1600px]">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

    <!-- header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
      <div>
        <h2 class="text-xl font-semibold text-gray-800">Users</h2>
        <p class="text-gray-600">Total: {{ total }}</p>
      </div>

      <div class="flex gap-2 w-full md:w-auto">
        <!-- search -->
        <div class="relative w-full max-w-xs">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search..."
            class="w-full h-10 px-3 pr-10 border border-gray-300 rounded-md text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-300"
          />
          <div class="absolute h-8 w-8 right-1 top-1 px-2 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
          </div>
        </div>

        <!-- add button -->
        <Link
          href="/users/create"
          class="flex justify-center items-center bg-gray-800 min-w-[150px] text-white text-sm font-bold px-4 py-2 rounded-md hover:bg-gray-700"
        >
          Add new user
        </Link>
      </div>
    </div>

    <!-- table -->
    <div class="overflow-auto max-h-[700px]">
      <table class="w-full border-collapse">
        <thead>
          <tr>
            <th v-for="header in headersConfig" :key="header.title" class="px-4 py-2 bg-gray-100 text-left text-gray-700 text-sm font-medium">
              {{ header.title }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100" @click="showUserInfo(user.id)">
            <td v-for="cellData in getUserCellData(user)" :key="cellData.data" class="px-4 py-2 text-gray-800 text-sm">
              {{ cellData.data }}
            </td>
            <td class="px-4 py-2">
              <Link :href="`users/${user.id}/edit`" class="rounded-md hover:bg-gray-200" @click.stop>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                </svg>
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!total" class="text-center py-10">No results found</div>
    </div>

    <!-- pagination -->
    <div v-if="!!total">
        Showing <strong>{{ from }}</strong>-<strong>{{ to }}</strong> of <strong>{{ total }}</strong>
    </div>
    <div v-if="!!total" class="flex flex-wrap gap-2 justify-center items-center mt-4">
      <Link
        v-for="(link, index) in links"
        :key="index"
        v-html="link.label"
        :href="link.url ?? ''"
        :class="[
            'px-4 py-2 border rounded-md text-gray-800 hover:bg-gray-100 min-w-fit', 
            !link.url ? 'pointer-events-none opacity-50' : '',
            link.active ? 'bg-gray-400 pointer-events-none' : ''
        ]"
      ></Link>
    </div>
  </div>

  <dialog ref="dialogRef" closedby="any" class="bg-white rounded-md p-10 m-auto min-w-[300px]">
    <p><strong>First name:</strong> {{ selectedUser?.first_name }}</p>
    <p><strong>Last name:</strong> {{ selectedUser?.last_name }}</p>
    <p><strong>Email:</strong> {{ selectedUser?.email }}</p>
    <p><strong>Country:</strong> {{ selectedUser?.address.country }}</p>
    <p><strong>City:</strong> {{ selectedUser?.address.city }}</p>
    <p><strong>Postal code:</strong> {{ selectedUser?.address.post_code }}</p>
    <p><strong>Street:</strong> {{ selectedUser?.address.address }}</p>
    <form method="dialog" class="flex justify-center mt-6">
      <button @click="hideUserInfo" class="flex justify-center items-center bg-gray-800 min-w-[150px] text-white text-sm font-bold px-4 py-2 rounded-md hover:bg-gray-700"
      >
      Close
      </button>
    </form>
  </dialog>
</template>
