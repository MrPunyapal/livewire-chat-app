<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AddMembers extends Component
{
    public Room $room;

    /**
     * @var Collection<int, User>
     */
    public Collection $allUsers;

    /**
     * @var Collection<int, User>
     */
    public Collection $existingMembers;

    /**
     * @var array<array-key, int>
     */
    public array $members = [];

    public function rules(): array
    {
        return [
            'members' => ['array', 'min:'.$this->existingMembers->count()],
            'members.*' => [
                'required',
                'exists:users,id',
            ],
        ];
    }

    public function mount(): void
    {
        $this->allUsers = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $this->members = $this->existingMembers
            ->pluck('id')
            ->all();
    }

    public function submit(): void
    {
        $this->validate();
        $this->room->users()->sync($this->members);

        $this->dispatch('members-added', id: $this->room->id);
    }

    public function render(): Factory|View
    {
        return view('livewire.rooms.add-members');
    }
}
