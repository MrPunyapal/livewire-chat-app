<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Flux\Flux;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[On('members-added')]
class RoomProfile extends Component
{
    use WithFileUploads;

    public int $roomId;

    public Room $room;

    public ?string $description = null;

    #[Validate('nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048')]
    public ?TemporaryUploadedFile $image = null;

    public function mount(): void
    {
        $this->room = Room::query()->findOrFail($this->roomId);

        abort_if(Gate::denies('show-roomProfile', $this->room), 403, 'You are not authorized to view this room profile.');

        $this->description = $this->room->description;
    }

    public function updatedImage(): void
    {
        $this->validateOnly('image');

        $image = $this->image;

        if (! $image instanceof TemporaryUploadedFile) {
            return;
        }

        $existingImage = $this->room->getRawOriginal('image');

        if (is_string($existingImage) && $existingImage !== '') {
            Storage::disk('public')->delete($existingImage);
        }

        $path = $image->store('room/'.$this->roomId, 'public');

        $this->room->update([
            'image' => $path,
        ]);

        $this->image = null;

        Flux::toast(variant: 'success', text: 'Room image updated successfully.');

        $this->dispatch('room-updated', roomId: $this->roomId);
    }

    public function saveDescription(): void
    {
        $this->validate([
            'description' => 'nullable|string|max:2000',
        ]);

        $this->room->update([
            'description' => (in_array($this->description, [null, '', '0'], true)) ? null : trim($this->description),
        ]);

        Flux::toast(variant: 'success', text: 'Room description changed.');

        $this->dispatch(
            'description-saved',
            roomId: $this->roomId,
            description: $this->room->description,
        );
    }

    public function render(): Factory|View
    {
        return view('livewire.rooms.room-profile', [
            'existingMembers' => $this->room->users,
        ]);
    }
}
