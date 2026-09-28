<!-- ════════════════════════════════════════════════════════════════ -->
<!-- AFI BİLİŞİM ÜRÜN KARŞILAŞTIRMA YÜZEN BAR (FLOATING COMPARE BAR) -->
<!-- ════════════════════════════════════════════════════════════════ -->
<div id="afi-compare-bar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-[99990] hidden transition-all duration-300">
    <div class="bg-[#12151c]/95 border border-yellow-500/40 rounded-full px-5 py-2.5 shadow-[0_15px_40px_rgba(0,0,0,0.8)] backdrop-blur-xl flex items-center gap-4">
        
        <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-scale-balanced"></i>
            </span>
            <span class="text-xs font-bold text-white hidden sm:inline">Karşılaştırma Listesi:</span>
        </div>

        <!-- Thumbnails Container -->
        <div id="compare-bar-thumbnails" class="flex items-center gap-1.5">
            <!-- Dynamic Thumbnails Rendered via JS -->
        </div>

        <div class="h-6 w-px bg-gray-800"></div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <a href="{{ route('compare.index') }}" 
               id="compare-bar-action-btn"
               class="bg-yellow-500 hover:bg-yellow-400 text-slate-950 font-black px-4 py-2 rounded-full transition text-xs flex items-center gap-1.5 shadow-md shadow-yellow-500/20 whitespace-nowrap">
                <span>Karşılaştır</span>
                <span id="compare-bar-badge" class="bg-slate-950 text-yellow-400 text-[10px] font-black px-1.5 py-0.2 rounded-full">0/4</span>
            </a>

            <button type="button" 
                    onclick="clearCompareBar()" 
                    class="text-gray-400 hover:text-red-400 p-1.5 rounded-full hover:bg-gray-800 transition" 
                    title="Temizle">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    refreshCompareBar();
});

async function refreshCompareBar() {
    try {
        const response = await fetch('/api/compare/list');
        const data = await response.json();
        updateCompareBarUI(data.list || []);
    } catch (err) {
        console.error("Compare bar güncelleme hatası:", err);
    }
}

function updateCompareBarUI(items) {
    const bar = document.getElementById('afi-compare-bar');
    const container = document.getElementById('compare-bar-thumbnails');
    const badge = document.getElementById('compare-bar-badge');
    if (!bar || !container) return;

    if (!items || items.length === 0) {
        bar.classList.add('hidden');
        container.innerHTML = '';
        if (badge) badge.innerText = '0/4';
        return;
    }

    bar.classList.remove('hidden');
    if (badge) badge.innerText = `${items.length}/4`;

    container.innerHTML = items.map(item => `
        <div class="relative group/thumb w-8 h-8 rounded-full bg-[#181c24] border border-gray-700 overflow-hidden flex items-center justify-center p-0.5" title="${escapeHtml(item.title)}">
            <img src="${item.image}" alt="${escapeHtml(item.title)}" class="object-contain w-full h-full">
            <button type="button" onclick="removeFromCompareBar(${item.id})" class="absolute inset-0 bg-red-500/90 text-white text-[10px] font-bold opacity-0 group-hover/thumb:opacity-100 flex items-center justify-center transition-opacity">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    `).join('');
}

async function toggleCompare(productId) {
    try {
        const response = await fetch(`/karsilastir/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        
        if (response.ok) {
            updateCompareBarUI(data.list || []);
            showCompareToast(data.message, data.status === 'added' ? 'success' : 'info');
        } else if (response.status === 422) {
            showCompareToast(data.message || 'Maksimum limite ulaşıldı.', 'warning');
        }
    } catch (err) {
        console.error("Karşılaştırma ekleme hatası:", err);
    }
}

async function removeFromCompareBar(productId) {
    await toggleCompare(productId);
}

async function clearCompareBar() {
    try {
        const response = await fetch('/karsilastir/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        updateCompareBarUI([]);
        showCompareToast('Karşılaştırma listesi temizlendi.', 'info');
    } catch (err) {
        console.error("Compare bar temizleme hatası:", err);
    }
}

function showCompareToast(msg, type) {
    const toast = document.createElement('div');
    const bgClass = type === 'warning' ? 'bg-amber-500 text-slate-950' : (type === 'success' ? 'bg-emerald-500 text-white' : 'bg-gray-800 text-white border border-gray-700');
    const icon = type === 'warning' ? 'fa-triangle-exclamation' : (type === 'success' ? 'fa-scale-balanced' : 'fa-circle-info');
    
    toast.className = `fixed bottom-20 left-1/2 transform -translate-x-1/2 ${bgClass} px-5 py-2.5 rounded-2xl shadow-2xl font-bold text-xs z-[99995] flex items-center gap-2 transition-all duration-300 translate-y-4 opacity-0`;
    toast.innerHTML = `<i class="fa-solid ${icon}"></i> <span>${msg}</span>`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}
</script>
