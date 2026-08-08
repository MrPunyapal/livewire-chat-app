<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use App\Models\User;
use Flux\Flux;
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
    public Collection $existingMembers;

    /**
     * @var array<int, int>
     */
    public array $members = [];

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'members' => [
                'required',
                'array',
                'min:1',
            ],
            'members.*' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function mount(): void
    {
        $this->members = [];
    }

    public function submit(): void
    {
        $this->validate();

        $this->room->users()->syncWithoutDetaching($this->members);

        $this->reset('members');

        $this->dispatch('members-added', id: $this->room->id);
        Flux::toast(variant: 'success', text: 'member(s) added to the group');
    }

    public function render(): Factory|View
    {
        $existingUserIds = $this->room->users()->pluck('users.id')->push($this->room->user_id)->unique();

        return view('livewire.rooms.add-members', [
            'users' => User::query()
                ->whereNotIn('id', $existingUserIds)
                ->orderBy('name')
                ->pluck('name', 'id'),
        ]);
    }
}
