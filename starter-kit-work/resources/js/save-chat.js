export const saveChat = () => ({
    async save() {
        if (this.$wire.message === '') return;
        await this.$wire.save();
        this.$wire.message = '';
    }
})
