<div>

    @if($para == 'taxistas')

        <div class="flex mb-4">
            <div class="form-switch mt-2">
                <input type="checkbox" id="g-permiso" class="sr-only" wire:change="changePermiso()" @if( $permiso ) checked @endif>
                <label class="bg-slate-400 dark:bg-slate-700" for="g-permiso">
                    <span class="bg-white shadow-sm" aria-hidden="true"></span>
                    <span class="sr-only"></span>
                    
                </label>
            </div>
            <span class="ml-2 mt-2">Permiso para Trabajar sin Crédito</span>
        </div>

        <div class="flex">
            <button class="btn bg-success-600 hover:bg-success-500 text-white mx-2" wire:click='entrar'>
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:target="entrar" wire:loading.class.remove="hidden" >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Entrar
            </button>
            <button class="btn bg-red-600 hover:bg-red-500 text-white mx-2" wire:click='sacar'>
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" wire:target="sacar" wire:loading.class.remove="hidden">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sacar
            </button>
        </div>

        <div class="w-full border border-slate-200 dark:border-slate-700 mt-2 mb-2"></div>
        <h4>Grupos a los que pertenece</h4>
        @foreach ($grupos as $grupo)
            <div class="flex">
                <div class="form-switch mt-2">
                    <input type="checkbox" id="g-{{ $grupo->id }}" class="sr-only" wire:change="change({{ $grupo->id }})" @if( $this->is($grupo->group_id) ) checked @endif>
                    <label class="bg-slate-400 dark:bg-slate-700" for="g-{{ $grupo->id }}">
                        <span class="bg-white shadow-sm" aria-hidden="true"></span>
                        <span class="sr-only"></span>
                        
                    </label>
                </div>
                <span class="ml-2 mt-2">{{ $grupo->label }}</span>
            </div>
        @endforeach

    @else

    @endif

</div>
