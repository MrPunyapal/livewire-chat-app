@props([
    'disabled' => false,
    'options' => [],
    'selected' => null,
    'name' => '',
    'id' => '',
    'placeholder' => '',
    'multiple' => false,
])

@php
    $id = $id ?: $name;
@endphp
<div class="w-full" x-data="alpineMuliSelect({selected:[], elementId:@js($id)})">

    <!-- Select Options -->
    <select {{ $disabled ? 'disabled' : '' }} class="hidden" id="{{ $id }}">
        @foreach ($options as $key => $value)
        <option value="{{ $key }}" data-search="{{ $value }}">{{ $value }}</option>
        @endforeach
    </select>

    <div class="w-full flex flex-col items-center h-8 mx-auto" @keyup.alt="toggle">
        <input id="{{ $id }}" name="{{ $name }}" type="hidden" x-bind:value="selectedValues()">

        <div class="inline-block relative w-full">

            <div class="flex flex-col items-center relative">

                <!-- Selected elements container -->
                <div class="w-full">
                    <div class="my-2 p-1 flex border border-gray-200 bg-white rounded-md">
                        <div class="flex flex-auto flex-wrap" x-on:click="open">
                            <!-- iterating over selected elements -->
                            <template x-for="(option,index) in selectedElms" :key="option.value">
                                <div x-show="index < 2"
                                    class="flex justify-center items-center m-1 font-medium py-1 px-2 rounded-full text-indigo-700 bg-indigo-100 border border-indigo-300 ">
                                    <div class="text-xs font-normal leading-none max-w-full flex-initial"
                                        x-model="selectedElms[option]" x-text="option.text"></div>
                                    <div class="flex flex-auto flex-row-reverse">
                                        <div x-on:click.stop="remove(index,option)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- More than two items selected -->
                            <div x-show="selectedElms.length > 2"
                                class="flex justify-center items-center m-1 font-medium py-1 px-2 rounded-full text-indigo-700 bg-indigo-100 border border-indigo-300 ">
                                <div
                                    class="text-xs font-normal h-6 flex justify-center items-center leading-none max-w-full flex-initial">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-indigo-200 text-pink-800 mr-2">
                                        <span x-text="selectedElms.length -2"></span>
                                    </span>
                                    more selected
                                </div>
                            </div>
                            <!-- None items selected -->
                            <div x-show="selectedElms.length == 0" class="flex-1">
                                <input placeholder="{{ $placeholder }}"
                                    class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                    x-bind:value="selectedElements()">
                            </div>
                        </div>
                        <!-- Drop down toogle with icons-->
                        <div class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200">
                            <button type="button" x-show="!isOpen()" x-on:click="open()"
                                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <button type="button" x-show="isOpen()" x-on:click="close()"
                                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Dropdown container -->
                <div class="w-full">
                    <div x-show.transition.origin.top="isOpen()" x-trap="isOpen()"
                        class="shadow-lg top-100 bg-white z-40 w-full lef-0 rounded" x-on:click.away="close">
                        <div class="flex flex-col w-full">

                            <div class="px-2 py-4 border-b-2">
                                <!-- Search input-->
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" autocomplete="off" id="search"
                                        x-model.debounce.750ms="search"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border border-indigo-300 rounded-md h-10"
                                        placeholder="" @keyup.escape="clear" @keyup.delete="deselect">
                                    <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                        <kbd class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 mr-2"
                                            x-on:click="clear">
                                            Esc
                                        </kbd>
                                        <kbd class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400"
                                            x-on:click="deselect">
                                            Del
                                        </kbd>
                                    </div>
                                </div>
                            </div>
                            <!-- Options container -->
                            <ul class="z-10 mt-0 w-full bg-white shadow-lg max-h-40 rounded-md py-0 text-base ring-1 ring-black ring-opacity-5 focus:outline-none  overflow-y-auto sm:text-sm"
                                tabindex="-1" role="listbox" @keyup.delete="deselect">
                                <template x-for="(option,index) in options" :key="option.text">
                                    <li class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-3"
                                        role="option">
                                        <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-slate-100"
                                            x-bind:class="option.selected ? 'bg-indigo-100' : ''"
                                            @click="select(index,$event)">
                                            <div x-bind:class="option.selected ? 'border-indigo-600' : ''"
                                                class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                                <div class="w-full items-center flex">
                                                    <div class="mx-2 leading-6" x-model="option" x-text="option.text">
                                                    </div>
                                                    <span
                                                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600"
                                                        x-show="option.selected">

                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>