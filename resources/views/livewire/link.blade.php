<li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if( in_array(Request::segment(1), ['apiwhatsapp'])){{ 'bg-slate-900' }}@endif">
    <a class="block text-slate-200 hover:text-white truncate transition duration-150 "
        href="{{ route('config_api') }}"
    >
        <div class="flex items-center">
            <i class="nav-icon fab fa-whatsapp text-slate-400 hover:text-indigo-500"></i>
            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">API WhatsApp</span>
        </div>
    </a>
</li>
