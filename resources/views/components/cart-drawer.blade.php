<!-- ════════════════════════════════════════════════════════════════ -->
<!-- AFI BİLİŞİM HIZLI SEPET / ÇEKMECE SEPET (SLIDE-OVER CART DRAWER) -->
<!-- ════════════════════════════════════════════════════════════════ -->

{{-- Backdrop Overlay --}}
<div id="cart-drawer-overlay" 
     onclick="closeCartDrawer()" 
     class="fixed inset-0 bg-black/75 backdrop-blur-sm z-[99990] opacity-0 pointer-events-none transition-opacity duration-300"></div>

{{-- Slide-over Panel --}}
<aside id="cart-drawer-panel" 
       class="fixed top-0 right-0 bottom-0 w-full sm:w-[440px] bg-[#12151c] border-l border-gray-800/80 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[99991] flex flex-col transform translate-x-full transition-transform duration-300 ease-out font-sans text-white">

    {{-- Header --}}
    <div class="p-5 border-b border-gray-800 bg-[#181c24] flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 flex items-center justify-center text-lg shadow-md">
                <i class="fa-solid fa-cart-shopping"></i>
            </span>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-black tracking-wide font-heading text-white">HIZLI SEPETİM</h2>
                    <span id="cart-drawer-count-badge" class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                        0 Ürün
                    </span>
                </div>
                <p class="text-[11px] text-gray-400">Sepetinizdeki ürünleri buradan anlık yönetebilirsiniz.</p>
            </div>
        </div>

        <button type="button" 
                onclick="closeCartDrawer()" 
                class="w-8 h-8 rounded-xl bg-gray-800/80 hover:bg-red-500 hover:text-white text-gray-400 flex items-center justify-center transition border border-gray-700">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    {{-- Free Shipping Bar --}}
    <div id="cart-drawer-shipping-banner" class="bg-yellow-500/10 border-b border-yellow-500/20 px-5 py-2.5 flex items-center gap-2 text-xs text-yellow-300 shrink-0">
        <i class="fa-solid fa-truck-fast text-yellow-400 text-sm"></i>
        <span id="cart-drawer-shipping-text">1.500 ₺ ve üzeri alışverişlerinizde kargo ücretsizdir!</span>
    </div>

    {{-- Product Items Container (Scrollable) --}}
    <div id="cart-drawer-items" class="flex-1 overflow-y-auto p-5 divide-y divide-gray-800/60 sidebar-scroll">
        {{-- Rendered via JS --}}
    </div>

    {{-- Footer --}}
    <div id="cart-drawer-footer" class="p-5 border-t border-gray-800 bg-[#181c24] shrink-0 space-y-4 shadow-inner">
        <div class="space-y-2 text-xs">
            <div class="flex justify-between text-gray-400">
                <span>Ara Toplam (KDV Dahil):</span>
                <span id="cart-drawer-subtotal" class="font-semibold text-white">0,00 ₺</span>
            </div>
            <div class="flex justify-between text-gray-400">
                <span>Kargo Ücreti:</span>
                <span id="cart-drawer-shipping-cost" class="font-bold text-emerald-400">ÜCRETSİZ</span>
            </div>
            <div class="flex justify-between items-baseline pt-2 border-t border-gray-800/80">
                <span class="text-sm font-bold text-gray-200">Genel Toplam:</span>
                <span id="cart-drawer-total" class="text-2xl font-black text-yellow-400 font-heading">0,00 ₺</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-1">
            <a href="{{ route('cart.index') }}" 
               class="w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-xl border border-gray-700 transition text-xs flex items-center justify-center gap-2">
                <i class="fa-solid fa-basket-shopping text-yellow-500"></i> Sepete Git
            </a>

            <a href="{{ route('checkout') }}" 
               class="w-full bg-gradient-btn text-afiDark font-black py-3 px-4 rounded-xl transition text-xs flex items-center justify-center gap-2 shadow-lg shadow-yellow-500/20">
                <span>Ödemeye Geç</span> <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</aside>

<script>
let isCartDrawerOpen = false;

function openCartDrawer() {
    const overlay = document.getElementById('cart-drawer-overlay');
    const panel = document.getElementById('cart-drawer-panel');
    if (!overlay || !panel) return;

    fetchCartDrawerData();

    overlay.classList.remove('opacity-0', 'pointer-events-none');
    overlay.classList.add('opacity-100');
    panel.classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
    isCartDrawerOpen = true;
}

function closeCartDrawer() {
    const overlay = document.getElementById('cart-drawer-overlay');
    const panel = document.getElementById('cart-drawer-panel');
    if (!overlay || !panel) return;

    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0', 'pointer-events-none');
    panel.classList.add('translate-x-full');
    document.body.style.overflow = '';
    isCartDrawerOpen = false;
}

async function fetchCartDrawerData() {
    try {
        const response = await fetch('/api/cart');
        const data = await response.json();
        renderCartDrawerUI(data);
    } catch (err) {
        console.error("Cart drawer verisi çekme hatası:", err);
    }
}

function renderCartDrawerUI(data) {
    const itemsContainer = document.getElementById('cart-drawer-items');
    const countBadge = document.getElementById('cart-drawer-count-badge');
    const subtotalEl = document.getElementById('cart-drawer-subtotal');
    const totalEl = document.getElementById('cart-drawer-total');
    const shippingText = document.getElementById('cart-drawer-shipping-text');
    const headerCartBadge = document.getElementById('cart-badge');

    // Header sepet rozet güncelleme
    if (headerCartBadge) {
        headerCartBadge.innerText = data.cartCount || 0;
        if (data.cartCount > 0) {
            headerCartBadge.classList.remove('hidden');
        } else {
            headerCartBadge.classList.add('hidden');
        }
    }

    if (countBadge) {
        countBadge.innerText = `${data.cartCount || 0} Ürün`;
    }

    if (subtotalEl) subtotalEl.innerText = data.formatted_total || '0,00 ₺';
    if (totalEl) totalEl.innerText = data.formatted_total || '0,00 ₺';

    if (shippingText) {
        if (data.total >= 1500) {
            shippingText.innerHTML = `<strong class="text-emerald-400">🎉 Tebrikler!</strong> Siparişiniz için kargo <b>ÜCRETSİZ</b>.`;
        } else {
            const diff = (1500 - (data.total || 0)).toLocaleString('tr-TR', { minimumFractionDigits: 2 });
            shippingText.innerHTML = `Kargo Ücretsiz fırsatı için <b>${diff} ₺</b> daha ürün ekleyin!`;
        }
    }

    if (!itemsContainer) return;

    if (!data.items || data.items.length === 0) {
        itemsContainer.innerHTML = `
            <div class="h-full flex flex-col items-center justify-center text-center p-8">
                <div class="w-16 h-16 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 flex items-center justify-center text-2xl mb-4 shadow-inner">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h3 class="text-base font-bold text-white mb-1">Sepetiniz Henüz Boş</h3>
                <p class="text-xs text-gray-400 mb-6 leading-relaxed">Ekipmanlarımızı ve bilgisayar modellerimizi hemen keşfedin!</p>
                <button type="button" onclick="closeCartDrawer()" class="bg-gray-800 hover:bg-gray-700 text-yellow-400 font-bold px-5 py-2.5 rounded-xl border border-gray-700 text-xs transition">
                    Alışverişe Başla
                </button>
            </div>
        `;
        return;
    }

    itemsContainer.innerHTML = data.items.map(item => `
        <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-3 group">
            <div class="w-16 h-16 bg-[#0b0f19] border border-gray-800 rounded-xl p-1.5 flex items-center justify-center shrink-0">
                <img src="${item.image}" alt="${escapeHtml(item.title)}" class="object-contain max-h-full max-w-full">
            </div>

            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-bold text-white line-clamp-2 leading-snug mb-1">
                    <a href="/products/${item.slug}" class="hover:text-yellow-400 transition-colors">${escapeHtml(item.title)}</a>
                </h4>
                <p class="text-xs font-black text-yellow-400 mb-2">${item.formatted_price}</p>

                <!-- Quantity Controls -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center bg-[#0b0f19] border border-gray-800 rounded-lg p-0.5">
                        <button type="button" 
                                onclick="updateCartDrawerQty(${item.id}, ${item.quantity - 1})" 
                                class="w-6 h-6 rounded-md hover:bg-gray-800 text-gray-400 hover:text-white flex items-center justify-center transition text-xs">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>
                        <span class="w-8 text-center text-xs font-bold text-white">${item.quantity}</span>
                        <button type="button" 
                                onclick="updateCartDrawerQty(${item.id}, ${item.quantity + 1})" 
                                class="w-6 h-6 rounded-md hover:bg-gray-800 text-gray-400 hover:text-white flex items-center justify-center transition text-xs">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </div>

                    <button type="button" 
                            onclick="removeCartDrawerItem(${item.id})" 
                            class="text-gray-500 hover:text-red-400 p-1 transition" title="Kaldır">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="text-right shrink-0">
                <span class="text-xs font-black text-gray-200">${item.line_total}</span>
            </div>
        </div>
    `).join('');
}

async function updateCartDrawerQty(productId, newQty) {
    try {
        const response = await fetch(`/sepet/guncelle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        });
        const data = await response.json();
        if (response.ok) {
            renderCartDrawerUI(data);
        } else {
            alert(data.message || 'Stok hatası');
        }
    } catch (err) {
        console.error("Sepet miktarı güncelleme hatası:", err);
    }
}

async function removeCartDrawerItem(productId) {
    try {
        const response = await fetch(`/sepet/kaldir/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        renderCartDrawerUI(data);
    } catch (err) {
        console.error("Sepetten kaldırma hatası:", err);
    }
}

{{-- Global Hızlı Sepete Ekleme Fonksiyonu --}}
async function addToCartQuick(btn, productId, quantity = 1) {
    let originalHtml = '';
    if (btn) {
        originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-xs"></i>`;
    }

    try {
        const response = await fetch(`/sepet/ekle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        });

        const data = await response.json();

        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }

        if (response.ok) {
            renderCartDrawerUI(data);
            openCartDrawer();
        } else {
            alert(data.message || 'Sepete eklenirken bir hata oluştu.');
        }
    } catch (err) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
        console.error("Sepete ekleme hatası:", err);
    }
}

function escapeHtml(str) {
    return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
