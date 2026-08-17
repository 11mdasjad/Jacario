<div x-data="{ 
        toasts: [],
        add(e) {
            const id = Date.now();
            this.toasts.push({
                id: id,
                message: e.detail.message,
                type: e.detail.type || 'success'
            });
            setTimeout(() => {
                this.remove(id);
            }, 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
     }" 
     @notify.window="add($event)" 
     class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2 pointer-events-none max-w-sm w-full">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="pointer-events-auto p-4 rounded-xl shadow-2xl border flex items-center justify-between space-x-3 text-xs font-medium backdrop-blur-md"
             :class="{
                 'bg-zinc-950/95 text-white border-zinc-800': toast.type === 'info',
                 'bg-[#0B0D10]/95 text-[#DFCAAB] border-[#C5A880]/40': toast.type === 'success',
                 'bg-rose-950/95 text-rose-100 border-rose-800': toast.type === 'error'
             }">
            <div class="flex items-center space-x-2.5">
                <template x-if="toast.type === 'success'">
                    <span class="w-2 h-2 rounded-full bg-[#C5A880] animate-ping"></span>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <span x-text="toast.message" class="leading-relaxed"></span>
            </div>
            <button @click="remove(toast.id)" class="text-zinc-400 hover:text-white shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
