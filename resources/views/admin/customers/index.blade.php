@extends('admin.layouts.app')

@section('title', 'Müşteriler | Admin Panel')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h1 class="text-3xl font-black text-white mb-2">Müşteriler</h1>
        <p class="text-slate-400">Sisteme kayıtlı tüm müşteri hesaplarını buradan yönetebilirsiniz.</p>
    </div>
    <div class="w-full md:w-auto">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="relative w-full md:w-72">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="İsim veya E-posta ara..." class="w-full bg-slate-800 border border-adminBorder rounded-lg pl-10 pr-4 py-2.5 text-white focus:outline-none focus:border-adminYellow transition-colors">
            <i class="fa-solid fa-search absolute left-3.5 top-3.5 text-slate-400"></i>
        </form>
    </div>
</div>

@if(session('success'))
<div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl mb-6">
    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6">
    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
</div>
@endif

<div class="bg-adminCard rounded-2xl border border-adminBorder overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-800/50 border-b border-adminBorder text-slate-400 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold">ID</th>
                    <th class="p-4 font-semibold">İsim</th>
                    <th class="p-4 font-semibold">E-Posta</th>
                    <th class="p-4 font-semibold">Kayıt Tarihi</th>
                    <th class="p-4 font-semibold text-center">Sipariş Sayısı</th>
                    <th class="p-4 font-semibold text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-adminBorder">
                @forelse($customers as $customer)
                <tr class="hover:bg-slate-800/20 transition-colors">
                    <td class="p-4 text-white font-medium">#{{ $customer->id }}</td>
                    <td class="p-4 text-white font-bold">{{ $customer->name }}</td>
                    <td class="p-4 text-slate-300">{{ $customer->email }}</td>
                    <td class="p-4 text-slate-400 text-sm">{{ $customer->created_at->format('d.m.Y H:i') }}</td>
                    <td class="p-4 text-center">
                        <span class="bg-slate-800 text-adminYellow font-bold px-3 py-1 rounded-full text-sm">
                            {{ $customer->orders_count ?? 0 }}
                        </span>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition-colors" title="Detay">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu kullanıcı hesabını silmek istediğinize emin misiniz? Bu işlem geri alınamaz!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-colors" title="Sil">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        <i class="fa-regular fa-face-frown text-4xl mb-3 block opacity-50"></i>
                        @if(request()->has('search') && request()->search != '')
                            Aradığınız kriterde kayıt bulunamadı.
                        @else
                            Henüz kayıtlı müşteri bulunmuyor.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($customers) && method_exists($customers, 'hasPages') && $customers->hasPages())
    <div class="p-4 border-t border-adminBorder bg-slate-800/30">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
