<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use App\Models\User;
use Closure;
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
    public Collection $allUsers;

    /**
     * @var Collection<int, User>
     */
    public Collection $existingMembers;

    /**
     * @var array<int,int>
     */
    public array $members = [];

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'members' => [
                'array',
                'min:'.$this->existingMembers->count(),
                function (string $attribute, mixed $value, Closure $fail): void {

                    /** @var list<int> $value */
                    $removedMembers = array_diff(
                        $this->existingMembers->modelKeys(),
                        $value,
                    );

                    if ($removedMembers !== []) {
                        $fail('Existing members cannot be removed.');
                    }
                },
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
        $this->allUsers = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $this->members = $this->existingMembers
            ->modelKeys();
    }

    public function submit(): void
    {
        $this->validate();
        $this->room->users()->sync($this->members);

        $this->dispatch('members-added', id: $this->room->id);
        Flux::toast(variant: 'success', text: 'member(s) added to the group');
    }

    public function render(): Factory|View
    {
        return view('livewire.rooms.add-members');
    }
}
