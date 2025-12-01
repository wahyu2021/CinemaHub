<div id="delete-modal" class="fixed inset-0 z-[110] bg-black/90 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Modal Panel -->
        <div class="relative inline-block align-bottom bg-[#111] rounded-2xl text-left overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-white/10 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full scale-95 opacity-0" id="delete-modal-panel">
            
            <!-- Decorative header line -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary via-red-500 to-primary"></div>
            
            <div class="px-6 pt-8 pb-6">
                <div class="sm:flex sm:items-start gap-5">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-red-500/10 sm:mx-0">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-2xl leading-6 font-display font-bold text-white" id="modal-title">
                            {{ __('messages.remove_confirmation') }}
                        </h3>
                        <div class="mt-3">
                            <p class="text-sm text-gray-400">
                                {{ __('messages.remove_warning') ?? "Are you sure you want to remove this movie? This action cannot be undone." }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/5 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <form id="delete-confirm-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-3 bg-primary text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-all hover:shadow-[0_0_20px_rgba(229,9,20,0.4)]">
                        {{ __('messages.remove_btn') ?? "Remove Movie" }}
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-white/10 shadow-sm px-6 py-3 bg-transparent text-base font-medium text-gray-300 hover:text-white hover:bg-white/10 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                    {{ __('messages.cancel') ?? "Cancel" }}
                </button>
            </div>
        </div>
    </div>
</div>
