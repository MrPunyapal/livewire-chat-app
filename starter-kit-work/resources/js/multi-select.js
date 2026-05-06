import Choices from 'choices.js'

export const multiSelect = (config = {}) => ({
    multiple: config.multiple !== undefined ? config.multiple : true,
    value: config.value || [],
    options: config.options || [],
    wireModel: config.wireModel || null,
    placeholder: config.placeholder || 'Select options',
    choices: null,

    init() {
        this.$nextTick(() => {
            this.choices = new Choices(this.$refs.select, {
                removeItemButton: true,
                searchEnabled: true,
                searchPlaceholderValue: 'Search...',
                placeholder: true,
                placeholderValue: this.placeholder,
                itemSelectText: '',
            })

            const refreshChoices = () => {
                let selection = this.multiple ? this.value : [this.value]

                this.choices.clearStore()
                this.choices.setChoices(
                    this.options.map(({ value, label }) => ({
                        value,
                        label,
                        selected: selection.includes(value),
                    }))
                )
            }

            refreshChoices()

            this.$refs.select.addEventListener('change', () => {
                this.value = this.choices.getValue(true)
                if (this.wireModel && typeof this.$wire !== 'undefined') {
                    this.$wire.set(this.wireModel, this.value, false)
                }
            })

            this.$watch('value', () => refreshChoices())
            this.$watch('options', () => refreshChoices())
        })
    },

    destroy() {
        if (this.choices) {
            this.choices.destroy()
        }
    }
})
