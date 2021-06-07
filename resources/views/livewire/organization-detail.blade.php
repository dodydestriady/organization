<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Organization')  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-4">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <div class="flex flex-row justify-between">
                            <h2 class="bg-gray-50 p-4 py-2 text-gray-500 font-medium">Profile</h2>
                            @can('manageOrganization', $organization)
                            <x-jet-button >
                                {{ __('Edit') }}
                            </x-jet-button>
                            @endcan
                        </div>
                        <div class="flex flex-row p-4">

                            <img class="h-28 w-28 rounded-full mr-4" src="{{ $organization->logo_url }}" alt="">
                            <div class="border-l-2 border-gray-100 pl-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $organization->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $organization->website }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $organization->email }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $organization->phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row justify-between">
                <h2>{{ __('Persons') }}</h2>
                @can('manageOrganization', $organization)
                <x-jet-button wire:click="addPerson" wire:loading.attr="disabled">
                    {{ __('Add Person') }}
                </x-jet-button>
                @endcan
            </div>
            <div class="bg-white shadow border-b border-gray-200 rounded-lg">
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
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($persons as $key => $data)
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
                                        <img class="h-10 w-10 rounded-full" src="{{$data->profile_photo_url}}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $data->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $data->role_organization }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $data->email }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $data->phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @can('manageOrganization', $organization)
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                |
                                <button wire:click="removePic({{$data->id}})" class="text-indigo-600 hover:text-indigo-900">{{ __('Remove') }}</button>
                                |
                                @endcan
                                @can('create', App\Models\Organization::class)
                                @if($data->role === App\Models\User::PIC)
                                <button wire:click="setAs({{$data->id}}, 'account')" class="text-indigo-600 hover:text-indigo-900">{{ __('Set As Account Manager') }}</button>
                                @elseif($data->role === App\Models\User::ACCOUNT_MANAGER)
                                <button wire:click="setAs({{$data->id}}, 'pic')" class="text-indigo-600 hover:text-indigo-900">{{ __('Set As PIC') }}</button>
                                @endif
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

        <x-jet-dialog-modal wire:model="openForm">
            <x-slot name="title">
                {{ $isEdit ? __('Edit Person') : __('Add Person') }}
            </x-slot>

            <x-slot name="content">
                <div class="col-span-6 sm:col-span-4">
                    <select class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" wire:model="createNew">
                        <option value="new"> {{ __('create new') }} </option>
                        @foreach ($pics as $pic)
                        <option value="{{$pic->id}}">{{$pic->name}}, {{$pic->email}}</option>
                        @endforeach
                    </select>
                </div>
                @if($createNew === 'new')
                <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                    <!-- Logo File Input -->
                    <input type="file" class="hidden" wire:model="person.photo" x-ref="photo" x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                    <x-jet-label for="photo" value="{{ __('Logo') }}" />

                    <!-- Current Logo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->person['profile_photo_url'] }}" alt="{{ $this->person['name'] }}" class="rounded-full h-20 w-20 object-cover">
                    </div>

                    <!-- New Logo Preview -->
                    <div class="mt-2" x-show="photoPreview">
                        <span class="block rounded-full w-20 h-20" x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select Picture') }}
                    </x-jet-secondary-button>

                    <x-jet-input-error for="photo" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="person.name" autocomplete="name" />
                    <x-jet-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="person.email" autocomplete="email" />
                    <x-jet-input-error for="email" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="phone" value="{{ __('Phone') }}" />
                    <x-jet-input id="phone" type="text" class="mt-1 block w-full" wire:model.defer="person.phone" autocomplete="phone" />
                    <x-jet-input-error for="phone" class="mt-2" />
                </div>
                @endif
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
</div>