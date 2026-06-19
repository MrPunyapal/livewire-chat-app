<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chats')]
class Chats extends Component
{

    public bool $showRoomProfile = false;

    public int $roomId;

    #[On('show-room-profile')]
    public function updateRoomProfile(int $roomId){
        $this->showRoomProfile = $roomId ? true : false;
        if($this->showRoomProfile){
            $this->roomId = $roomId;
        }
    }

    public function render(): View
    {
        return view('livewire.pages.chats');
    }
}
