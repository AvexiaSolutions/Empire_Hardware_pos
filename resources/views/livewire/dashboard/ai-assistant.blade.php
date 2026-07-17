<div>
    <template x-teleport="body">
        <div x-data="{ open: false }" class="position-fixed p-4" style="z-index: 99999; bottom: 30px !important; right: 30px !important; left: auto !important;">
            
            <style>
                @keyframes aiPulseGlow {
                    0% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.6); }
                    70% { box-shadow: 0 0 0 15px rgba(139, 92, 246, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0); }
                }
                .ai-fab-btn {
                    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                    animation: aiPulseGlow 2.5s infinite;
                }
                .ai-fab-btn:hover {
                    animation: none;
                    box-shadow: 0 15px 30px -5px rgba(139, 92, 246, 0.6);
                    transform: scale(1.1) translateY(-5px);
                }
            </style>

            <!-- Chat Button (FAB) -->
            <button @click="open = !open" 
                    class="ai-fab-btn rounded-circle d-flex align-items-center justify-content-center border-0 position-relative shadow-lg" 
                    style="width: 65px; height: 65px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);"
                    x-bind:style="open ? 'transform: scale(0); opacity: 0;' : 'opacity: 1;'"
                    x-on:mouseleave="!open && (this.style.transform='')">
                <!-- Uploaded AI Icon -->
                <img src="{{ asset('images/ai-icon.png') }}" alt="AI" class="img-fluid rounded-circle" style="width: 42px; height: 42px; object-fit: cover;">
            </button>

            <!-- Chat Box -->
            <div x-cloak
                 x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="card shadow-lg border rounded-4 overflow-hidden flex-column bg-body-tertiary" 
                 :class="open ? 'd-flex' : 'd-none'"
                 style="width: 380px; height: 550px; max-height: calc(100vh - 40px); position: absolute; bottom: 1.5rem; right: 1.5rem; border-color: var(--bs-border-color) !important; transform-origin: bottom right;">
                 
                 <!-- Header -->
        <div class="card-header bg-body p-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fs-6 fw-bold d-flex align-items-center gap-2 text-body">
                <!-- Sparkle AI Icon -->
                <img src="{{ asset('images/ai-icon.png') }}" alt="AI" class="img-fluid rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                Empire AI Assistant
            </h5>
            <button @click="open = false" type="button" class="btn-close" aria-label="Close"></button>
        </div>

        <div class="card-body p-0 d-flex flex-column flex-grow-1 overflow-hidden">
            <!-- Chat History -->
            <div class="flex-grow-1 p-3 overflow-auto" id="chat-container">
                @foreach($messages as $index => $message)
                    <div class="d-flex mb-3 {{ $message['role'] == 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="d-flex align-items-end gap-2" style="max-width: 85%;">
                            @if($message['role'] == 'model')
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm overflow-hidden" style="width: 32px; height: 32px;">
                                    <img src="{{ asset('images/ai-icon.png') }}" alt="AI" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endif
                            
                            <div class="p-2 px-3 rounded-4 shadow-sm {{ $message['role'] == 'user' ? 'bg-primary text-white text-end' : 'bg-body border text-body' }}" style="border-bottom-{{ $message['role'] == 'user' ? 'right' : 'left' }}-radius: 4px; font-size: 0.95rem; line-height: 1.5;">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($isLoading)
                    <div class="d-flex mb-3 justify-content-start">
                        <div class="d-flex align-items-end gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm overflow-hidden" style="width: 32px; height: 32px;">
                                <img src="{{ asset('images/ai-icon.png') }}" alt="AI" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="p-2 px-3 rounded-4 shadow-sm bg-body border text-body d-flex gap-1" style="border-bottom-left-radius: 4px;">
                                <span class="spinner-grow spinner-grow-sm text-primary" role="status" aria-hidden="true" style="width: 0.5rem; height: 0.5rem;"></span>
                                <span class="spinner-grow spinner-grow-sm text-primary" role="status" aria-hidden="true" style="animation-delay: 0.1s; width: 0.5rem; height: 0.5rem;"></span>
                                <span class="spinner-grow spinner-grow-sm text-primary" role="status" aria-hidden="true" style="animation-delay: 0.2s; width: 0.5rem; height: 0.5rem;"></span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Input Area -->
            <div class="p-3 bg-body border-top">
                <form wire:submit.prevent="sendMessage">
                    <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" wire:model="userInput" class="form-control border-0 bg-transparent text-body px-4 shadow-none fs-6" placeholder="Ask AI..." required {{ $isLoading ? 'disabled' : '' }}>
                        <button type="submit" class="btn btn-primary px-4 fw-bold d-flex align-items-center justify-content-center" {{ $isLoading ? 'disabled' : '' }}>
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
        </div>
    </template>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            let container = document.getElementById('chat-container');
            if (container) container.scrollTop = container.scrollHeight;
            
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    setTimeout(() => {
                        let container = document.getElementById('chat-container');
                        if (container) container.scrollTop = container.scrollHeight;
                    }, 50);
                })
            });
        });
    </script>
</div>
