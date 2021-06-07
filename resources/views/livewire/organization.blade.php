<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organization') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('create', App\Models\Organization::class)
            <x-jet-button wire:click="addRecord" wire:loading.attr="disabled">
                {{ __('Add Organization') }}
            </x-jet-button>
            @endcan
            <div class="flex flex-col py-4">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Profile
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <!-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Account Manager
                                        </th> -->
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($organizations as $key => $data)

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $key + 1 }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{$data->logo_url}}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $data->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $data->website }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $data->email }}</div>
                                            <div class="text-sm text-gray-500">{{ $data->phone }}</div>
                                        </td>
                                        <!-- <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=60" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $data->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $data->website }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td> -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('manageOrganization', $data)
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            |
                                            @endcan
                                            <a href="{{route('organization.detail', $data->id)}}" class="text-indigo-600 hover:text-indigo-900">Show</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <x-jet-dialog-modal wire:model="openForm">
        <x-slot name="title">
            {{ $isEdit ? __('Edit Organization') : __('New Organization') }}
        </x-slot>

        <x-slot name="content">
            <div x-data="{logoName: null, logoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Logo File Input -->
                <input type="file" class="hidden" wire:model="organization.logo" x-ref="logo" x-on:change="
                                    logoName = $refs.logo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        logoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.logo.files[0]);
                            " />

                <x-jet-label for="logo" value="{{ __('Logo') }}" />

                <!-- Current Logo -->
                <div class="mt-2" x-show="! logoPreview">
                    <img src="{{ $this->organization['logo_url'] }}" alt="{{ $this->organization['name'] }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Logo Preview -->
                <div class="mt-2" x-show="logoPreview">
                    <span class="block rounded-full w-20 h-20" x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + logoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.logo.click()">
                    {{ __('Select A New Logo') }}
                </x-jet-secondary-button>

                <x-jet-input-error for="logo" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="organization.name" autocomplete="name" />
                <x-jet-input-error for="name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="organization.email" autocomplete="email" />
                <x-jet-input-error for="email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="phone" value="{{ __('Phone') }}" />
                <x-jet-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="organization.phone" autocomplete="phone" />
                <x-jet-input-error for="phone" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="website" value="{{ __('Website') }}" />
                <x-jet-input id="website" type="text" class="mt-1 block w-full" wire:model.defer="organization.website" autocomplete="website" />
                <x-jet-input-error for="website" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('openForm')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="submit" for="form" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>