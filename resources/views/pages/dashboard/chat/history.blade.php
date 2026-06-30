<?php

use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Models\Conversation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $search = '';

    public ?string $activeConversationId = null;

    #[Computed]
    public function conversations()
    {
        return Conversation::where('user_id', Auth::id())
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function selectConversation(string $id, string $title): void
    {
        $this->activeConversationId = $id;
        $this->dispatch('load-conversation', id: $id);
        $this->dispatch('conversation-selected', id: $id, title: $title);
    }

    #[On('conversation-started')]
    public function onConversationStarted(string $id): void
    {
        $this->activeConversationId = $id;
        unset($this->conversations);
    }

    #[On('new-conversation')]
    public function onNewConversation(): void
    {
        $this->activeConversationId = null;
    }
}
?>

<div>
    <flux:input
        wire:model.live.debounce.300ms="search"
        icon="magnifying-glass"
        placeholder="{{ __('Search chats') }}"
        class="[&>input]:bg-zinc-50! [&>input]:h-9! [&>input]:focus:outline-none! [&>input]:focus:ring-0!"
    />

    <div class="mt-2 space-y-0.5">
        @forelse ($this->conversations as $conversation)
            <button
                wire:click="selectConversation({{ Js::from($conversation->id) }}, {{ Js::from($conversation->title) }})"
                wire:key="conv-{{ $conversation->id }}"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $activeConversationId === $conversation->id ? 'bg-orange-100 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 font-medium' : 'text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}"
            >
                <div class="truncate">{{ $conversation->title }}</div>
                <div class="text-xs text-zinc-400 mt-0.5">{{ $conversation->updated_at->diffForHumans() }}</div>
            </button>
        @empty
            @if ($search)
                <p class="px-3 py-4 text-center text-zinc-400 text-xs">{{ __('No chats found') }}</p>
            @endif
        @endforelse
    </div>
</div>
