<div class="overflow-y-auto max-h-[600px]">
    <form wire:submit="store" class="space-y-6">
        <div>
            <x-input-label for="name" value="Room Name" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="name" id="name" name="name" type="text"
                class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                required autofocus autocomplete="name" placeholder="Enter room name..." />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="members" value="Invite Members" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
            
            <x-multi-select-input id="members" name="members" :options="$users" placeholder="select members" />
            
            <x-input-error class="mt-2" :messages="$errors->get('members')" />
        </div>

        <div>
            <div class="flex items-center justify-end gap-4 pt-4 mt-12 border-t border-gray-200 dark:border-gray-600">
                <x-action-message class="text-green-600 dark:text-green-400" on="room-created">
                    {{ __('Room created successfully!') }}
                </x-action-message>
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 px-6 py-2.5">
                    {{ __('Create Room') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</div>
@script
<script>
    Alpine.data("alpineMuliSelect", (obj) => ({
        elementId: obj.elementId,
        options: [],
        selected: obj.selected,
        selectedElms: [],
        show: false,
        search: '',
        open() {
            this.show = true
        },
        close() {
            this.show = false
        },
        toggle() {
            this.show = !this.show
        },
        isOpen() {
            return this.show === true
        },

        // Initializing component 
        init() {
            const options = document.getElementById(this.elementId).options;
            for (let i = 0; i < options.length; i++) {

                this.options.push({
                    value: options[i].value,
                    text: options[i].innerText,
                    search: options[i].dataset.search,
                    selected: Object.values(this.selected).includes(options[i].value)
                });

                if (this.options[i].selected) {
                    this.selectedElms.push(this.options[i])
                }
            }


            // searching for the given value
            this.$watch("search", (e => {
                this.options = []
                const options = document.getElementById(this.elementId).options;
                Object.values(options).filter((el) => {
                    var reg = new RegExp(this.search, 'gi');
                    return el.dataset.search.match(reg)
                }).forEach((el) => {
                    let newel = {
                        value: el.value,
                        text: el.innerText,
                        search: el.dataset.search,
                        selected: Object.values(this.selected).includes(el.value)
                    }
                    this.options.push(newel);

                })


            }));
        },
        // clear search field
        clear() {
            this.search = ''
        },
        // deselect selected options
        deselect() {
            setTimeout(() => {
                this.selected = []
                this.selectedElms = []
                Object.keys(this.options).forEach((key) => {
                    this.options[key].selected = false;
                })
            }, 100)
        },
        // select given option
        select(index, event) {
            if (!this.options[index].selected) {
                this.options[index].selected = true;
                this.options[index].element = event.target;
                this.selected.push(this.options[index].value);
                this.selectedElms.push(this.options[index]);

            } else {
                this.selected.splice(this.selected.lastIndexOf(index), 1);
                this.options[index].selected = false
                Object.keys(this.selectedElms).forEach((key) => {
                    if (this.selectedElms[key].value == this.options[index].value) {
                        setTimeout(() => {
                            this.selectedElms.splice(key, 1)
                        }, 100)
                    }
                })
            }
        },
        // remove from selected option
        remove(index, option) {
            this.selectedElms.splice(index, 1);
            Object.keys(this.selected).forEach((skey) => {
                if (this.selected[skey] == option.value) {
                    this.selected.splice(skey, 1);
                }
            });
            Object.keys(this.options).forEach((key) => {
                if (this.options[key].value == option.value) {
                    this.options[key].selected = false;
                }
            });
        },
        // filter out selected elements
        selectedElements() {
            return this.options.filter(op => op.selected === true)
        },
        // get selected values
        selectedValues() {
            let selectedOptions = this.options.filter(op => op.selected === true).map(el => el.value)
            console.log('value of selected options' + selectedOptions);
            $wire.set('members', selectedOptions, false);
            return selectedOptions;
        }
    }));
    </script>
    @endscript