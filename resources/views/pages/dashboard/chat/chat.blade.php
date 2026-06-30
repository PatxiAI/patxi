<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Models\Conversation;
use Laravel\Ai\Models\ConversationMessage;
use Livewire\Attributes\On;
use Livewire\Component;
use PatxiAI\PatxiCore\Ai\Agents\Patxi;
use PatxiAI\PatxiCore\Models\AgentChainStep;

new class extends Component
{
    public array $messages = [];

    public string $input = '';

    public ?string $conversationId = null;

    public function mount(?string $conversationId = null): void
    {
        if ($conversationId) {
            $this->conversationId = $conversationId;
            $this->loadMessages();
        }
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->input))) {
            return;
        }

        $userMessage = trim($this->input);
        $this->input = '';

        $this->messages[] = ['role' => 'user', 'content' => $userMessage];

        $agent = new Patxi;

        if ($this->conversationId) {
            $agent->continue($this->conversationId, Auth::user());
        } else {
            $agent->forUser(Auth::user());
        }

        $response = $agent->prompt($userMessage, provider: Lab::Gemini, model: 'gemini-2.5-flash');

        if ($response->conversationId) {
            $isNew = $this->conversationId === null;
            $this->conversationId = $response->conversationId;

            if ($isNew) {
                $title = Conversation::find($this->conversationId)?->title ?? $userMessage;
                $this->dispatch('conversation-started', id: $this->conversationId, title: $title);
            }
        }

        // Reload from DB so chain steps (persisted by the tracker after the prompt) are included.
        $this->loadMessages();
    }

    #[On('load-conversation')]
    public function loadConversation(string $id): void
    {
        $this->conversationId = $id;
        $this->input = '';
        $this->messages = [];
        $this->loadMessages();
    }

    #[On('new-conversation')]
    public function newConversation(): void
    {
        $this->conversationId = null;
        $this->input = '';
        $this->messages = [];
    }

    private function loadMessages(): void
    {
        $dbMessages = ConversationMessage::where('conversation_id', $this->conversationId)
            ->orderBy('created_at')
            ->get(['id', 'role', 'content', 'tool_results']);

        $chainSteps = AgentChainStep::where('conversation_id', $this->conversationId)
            ->orderBy('sort_order')
            ->get(['conversation_message_id', 'caller_agent', 'called_agent', 'depth'])
            ->groupBy('conversation_message_id');

        $this->messages = $dbMessages->map(function ($m) use ($chainSteps) {
            $content = $m->content;

            // Fallback: if Patxi returned empty text, surface the last tool result directly.
            if (empty($content) && ! empty($m->tool_results)) {
                $content = collect($m->tool_results)->last()['result'] ?? '';
            }

            return [
                'role' => $m->role,
                'content' => $content,
                'chain' => $this->buildChain($chainSteps->get($m->id, collect())),
            ];
        })->toArray();
    }

    /** Build a flat ordered path of unique agent names from the chain steps. */
    private function buildChain(Collection $steps): array
    {
        if ($steps->isEmpty()) {
            return [];
        }

        $chain = [$steps->first()->caller_agent];

        foreach ($steps as $step) {
            if (end($chain) !== $step->called_agent) {
                $chain[] = $step->called_agent;
            }
        }

        return $chain;
    }
}
?>

<div class="p-6 mx-auto w-full max-w-3xl flex flex-col flex-1 min-h-0">
    <!-- Messages -->
    <div class="grow overflow-y-auto min-h-0 space-y-8 pb-4 lg:pb-12">
        @forelse ($messages as $message)
            @if ($message['role'] === 'user')
                <div class="flex justify-end">
                    <div class="max-w-[75%] rounded-2xl px-4 py-2.5 bg-orange-500 text-white text-sm">
                        {{ $message['content'] }}
                    </div>
                </div>
            @else
                <div class="flex items-start justify-start gap-4">
                    <div class="size-8 shrink-0">
                        <x-patxiai-patxi::app-logo-icon />
                    </div>
                    <div class="min-w-0 flex-1">

                        @if (!empty($message['chain']))
                            <div class="flex flex-wrap items-center gap-1 mb-4">
                                @foreach ($message['chain'] as $i => $agentName)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800">
                                        <svg class="size-3 shrink-0" viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="5" r="3"/><path d="M2 13c0-3.3 2.7-6 6-6s6 2.7 6 6H2z"/></svg>
                                        {{ $agentName }}
                                    </span>
                                    @if (!$loop->last)
                                        <svg class="size-3 text-zinc-400 shrink-0" viewBox="0 0 16 16" fill="currentColor"><path d="M6 3l5 5-5 5"/></svg>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="markdown">
                            {!! Str::markdown($message['content'] ?? '') !!}
                        </div>

                    </div>
                </div>
            @endif
        @empty
            <div class="flex items-center justify-center h-full text-zinc-400 text-sm">
                {{ __('Start a conversation with Patxi') }}
            </div>
        @endforelse
    </div>
    <!-- /Messages -->

    <!-- Input Area -->
    <div class="shrink-0">
        <flux:card class="mb-2 p-2">
            <form wire:submit="sendMessage" x-data @new-conversation.window="$nextTick(() => $el.querySelector('textarea')?.focus())">
                <flux:textarea autofocus wire:model="input" rows="auto" resize="none" class="mb-2 border-0 shadow-none! focus:outline-none focus:ring-0" placeholder="{{ __('Patxi is ready for you...') }}"
                    @keydown.enter.prevent.stop="if (!$event.shiftKey) { $el.closest('form').requestSubmit() }"
                />
                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        <flux:icon.paper-clip class="size-5 text-gray-500"/>
                        <flux:icon.globe-alt class="size-5 text-gray-500"/>
                    </div>

                    <flux:button type="submit" icon="arrow-up" variant="primary" color="orange" wire:loading.attr="disabled" />
                </div>
            </form>
        </flux:card>
        <flux:text class="text-center text-xs">{{ __('AI can make mistakes. Verify important information') }}</flux:text>
    </div>
    <!-- /Input Area -->
</div>
