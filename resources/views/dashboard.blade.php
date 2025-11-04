<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('dashboard') }}" class="mx-auto flex flex-nowrap items-center gap-3">
                        <x-input-label for="search" class="mr-2 whitespace-nowrap">Search by slug:</x-input-label>
                        <x-text-input value="{{ $search }}" class="mr-2 w-56 sm:w-72" name="search"/>
                        @isset($search)
                            <x-link-primary-button link="{{ route('dashboard') }}">Clear</x-link-primary-button>
                        @else
                            <x-primary-button>Search</x-primary-button>
                        @endisset
                    </form>
                    <div class="overflow-x-auto pt-2">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead>
                            <tr>
                                <th>Target URL</th>
                                <th>Slug</th>
                                <th>Hits</th>
                                <th>Temporary stats link</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($links ?? [] as $link)
                                <tr class="dark:text-white" x-data="{ val: @js($link->signed_link), copied: false }">
                                    <td>{{ $link->target_url }}</td>
                                    <td >{{ $link->slug }}</td>
                                    <td class="text-center">{{ $link->hitsCount }}</td>
                                    <td class="text-center"><x-copy-button/></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-2">
                        {!! $links !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
