<style>
    /* Chatbot Messages Scrollbar & Overflow Styling */
    #chat-messages {
        overflow-x: hidden !important;
        overflow-y: auto !important;
        word-break: break-word;
        box-sizing: border-box;
    }

    #chat-messages * {
        box-sizing: border-box;
        max-width: 100%;
    }

    /* Custom Webkit Scrollbar */
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: #0b0f19;
        border-radius: 8px;
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background: #eab308;
        border-radius: 9999px;
        border: 1px solid #181c24;
    }

    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: #facc15;
    }

    /* Firefox Scrollbar Support */
    #chat-messages {
        scrollbar-width: thin;
        scrollbar-color: #eab308 #0b0f19;
    }
</style>

<!-- ════════════════════════════════════════════════════════════════ -->
<!-- AFI BİLİŞİM CANLI DESTEK & ASİSTAN CHATBOT WIDGET                 -->
<!-- ════════════════════════════════════════════════════════════════ -->
<div id="afi-chatbot-container" class="fixed bottom-6 right-6 z-[99999] font-sans" x-data="{ open: false, unread: true }">
    
    <!-- Chatbot Floating Launcher Button -->
    <button type="button" 
            @click="open = !open; unread = false" 
            class="relative group bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-400 hover:to-amber-400 text-slate-950 p-4 rounded-full shadow-[0_10px_30px_rgba(234,179,8,0.4)] flex items-center justify-center transition-all duration-300 transform hover:scale-110 border-2 border-yellow-300/40">
        
        <i x-show="!open" class="fa-solid fa-headset text-2xl group-hover:rotate-12 transition-transform"></i>
        <i x-show="open" class="fa-solid fa-xmark text-2xl transition-transform" style="display: none;"></i>

        <!-- Unread Pulse Badge -->
        <span x-show="unread && !open" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-950 animate-ping"></span>
        <span x-show="unread && !open" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-slate-950 flex items-center justify-center text-[8px] font-black text-white">1</span>
    </button>

    <!-- Chatbot Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="absolute bottom-20 right-0 w-[350px] sm:w-[390px] h-[520px] bg-[#12151c] border border-gray-800 rounded-3xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.9)] overflow-hidden flex flex-col backdrop-blur-xl z-50"
         style="display: none;">

        <!-- Header -->
        <div class="bg-[#181c24] p-4 border-b border-gray-800 flex items-center justify-between relative">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 flex items-center justify-center text-lg font-bold shadow-md">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-[#181c24]"></span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-white flex items-center gap-1.5 font-heading">
                        <span>Afi Canlı Asistan</span>
                        <span class="bg-yellow-500/10 text-yellow-400 text-[9px] font-bold px-1.5 py-0.5 rounded border border-yellow-500/20">AI BOT</span>
                    </h3>
                    <p class="text-[11px] text-gray-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> 7/24 Çevrimiçi Destek
                    </p>
                </div>
            </div>
            
            <button @click="open = false" class="text-gray-400 hover:text-white p-2 rounded-lg hover:bg-gray-800 transition">
                <i class="fa-solid fa-minus text-sm"></i>
            </button>
        </div>

        <!-- Chat Messages Container -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 sidebar-scroll bg-[#0b0f19]/60 text-xs">
            
            <!-- Bot Initial Message -->
            <div class="flex items-start gap-2.5 max-w-[85%]">
                <div class="w-7 h-7 rounded-xl bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 flex items-center justify-center shrink-0 mt-1">
                    <i class="fa-solid fa-robot text-xs"></i>
                </div>
                <div class="bg-[#181c24] border border-gray-800 text-gray-200 p-3 rounded-2xl rounded-tl-none shadow-md space-y-2 leading-relaxed">
                    <p>👋 Merhaba! Ben <b>Afi Asistan</b>. Afi Bilişim'e hoş geldiniz!</p>
                    <p>Size nasıl yardımcı olabilirim? Aşağıdaki hızlı sorgulardan birini seçebilir veya sorunuzu yazabilirsiniz:</p>
                </div>
            </div>

            <!-- Quick Action Pills -->
            <div class="flex flex-wrap gap-1.5 pt-1 pl-9" id="quick-pills">
                <button type="button" onclick="handleQuickAction('budget')" class="bg-amber-500/10 hover:bg-yellow-500 hover:text-slate-950 text-amber-400 font-bold px-2.5 py-1.5 rounded-xl border border-amber-500/30 transition text-[11px] flex items-center gap-1 shadow-sm">
                    💰 Bütçeme Göre Öner
                </button>
                <button type="button" onclick="handleQuickAction('serial')" class="bg-gray-800/80 hover:bg-yellow-500 hover:text-slate-950 text-gray-300 font-bold px-2.5 py-1.5 rounded-xl border border-gray-700 transition text-[11px] flex items-center gap-1">
                    🏷️ Seri No Sorgula
                </button>
                <button type="button" onclick="handleQuickAction('order')" class="bg-gray-800/80 hover:bg-yellow-500 hover:text-slate-950 text-gray-300 font-bold px-2.5 py-1.5 rounded-xl border border-gray-700 transition text-[11px] flex items-center gap-1">
                    📦 Sipariş Takibi
                </button>
                <button type="button" onclick="handleQuickAction('service')" class="bg-gray-800/80 hover:bg-yellow-500 hover:text-slate-950 text-gray-300 font-bold px-2.5 py-1.5 rounded-xl border border-gray-700 transition text-[11px] flex items-center gap-1">
                    🔧 Servis Sorgula
                </button>
                <button type="button" onclick="handleQuickAction('support')" class="bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-emerald-400 font-bold px-2.5 py-1.5 rounded-xl border border-emerald-500/30 transition text-[11px] flex items-center gap-1">
                    🎧 İletişim & Destek
                </button>
            </div>

        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="chat-typing" class="px-4 py-2 bg-[#0b0f19] hidden text-[11px] text-yellow-400 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-yellow-400 animate-ping"></span>
            <span>Afi Asistan yanıt yazıyor...</span>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-[#181c24] border-t border-gray-800">
            <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
                <input type="text" 
                       id="chat-input" 
                       placeholder="Mesajınızı veya takip kodunuzu yazın..." 
                       class="flex-1 bg-[#0b0f19] text-white border border-gray-800 rounded-xl px-3.5 py-2.5 text-xs outline-none focus:border-yellow-500/60 transition-colors placeholder-gray-500"
                       autocomplete="off">
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-400 text-slate-950 p-2.5 rounded-xl transition-all font-bold flex items-center justify-center shrink-0 shadow-md shadow-yellow-500/20">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
async function handleChatSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    appendUserMessage(msg);
    input.value = '';
    showTyping(true);

    try {
        const response = await fetch('/api/chatbot/query', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: msg })
        });

        const data = await response.json();
        showTyping(false);

        if (data.reply) {
            appendBotMessage(data.reply);
        } else {
            appendBotMessage("Anlayamadım, lütfen sorunuzu tekrar yazabilir misiniz?");
        }
    } catch (err) {
        showTyping(false);
        appendBotMessage("Şu an bağlantı kurulamıyor. Lütfen daha sonra tekrar deneyin veya WhatsApp destek hattımızdan bize ulaşın.");
    }
}

function handleQuickAction(type) {
    if (type === 'budget') {
        appendBotMessage("💰 <b>Bütçe & Ürün Danışmanı</b><br>Aradığınız ürünü veya bütçenizi yazabilirsiniz:<br>• <i>'50 bin TL altı kasalar'</i><br>• <i>'20.000 TL altı laptop'</i><br>• <i>'Oyuncu bilgisayarı'</i>");
        focusChatInput('');
    } else if (type === 'serial') {
        appendBotMessage("🏷️ <b>Seri No / SKU Sorgulama</b><br>Lütfen sorgulamak istediğiniz ürünün seri numarasını giriniz:<br>(Örn: <code>AFI-SRN-00001</code>)");
        focusChatInput('AFI-SRN-');
    } else if (type === 'order') {
        appendBotMessage("📦 <b>Sipariş Takip Asistanı</b><br>Lütfen sipariş kodunuzu giriniz:<br>(Örn: <code>AFI-TXN-123456</code> veya <code>#1234</code>)");
        focusChatInput('AFI-TXN-');
    } else if (type === 'service') {
        appendBotMessage("🔧 <b>Teknik Servis Takibi</b><br>Lütfen servis takip kodunuzu giriniz:<br>(Örn: <code>SR-123456</code>)");
        focusChatInput('SR-');
    } else if (type === 'support') {
        appendBotMessage("🎧 <b>Canlı Destek & İletişim Bilgileri</b><br>• <b>Telefon:</b> <a href='tel:+905555555555' class='text-yellow-400 font-bold'>0555 555 55 55</a><br>• <b>WhatsApp 7/24:</b> <a href='https://wa.me/905555555555' target='_blank' class='text-emerald-400 font-bold underline'>WhatsApp İle Bağlan</a><br>• <b>E-Posta:</b> info@afibilisim.com");
    }
}

function focusChatInput(prefix) {
    const input = document.getElementById('chat-input');
    if (input) {
        input.value = prefix;
        input.focus();
    }
}

function sendQuickMessage(text) {
    document.getElementById('chat-input').value = text;
    handleChatSubmit();
}

function appendUserMessage(text) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex items-start justify-end gap-2 max-w-[85%] ml-auto';
    div.innerHTML = `
        <div class="bg-yellow-500 text-slate-950 font-semibold p-3 rounded-2xl rounded-tr-none shadow-md text-xs leading-relaxed">
            ${escapeHtml(text)}
        </div>
    `;
    container.appendChild(div);
    scrollToBottom();
}

function appendBotMessage(htmlContent) {
    const container = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'flex items-start gap-2.5 max-w-[85%] animate-fadeIn';
    div.innerHTML = `
        <div class="w-7 h-7 rounded-xl bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 flex items-center justify-center shrink-0 mt-1">
            <i class="fa-solid fa-robot text-xs"></i>
        </div>
        <div class="bg-[#181c24] border border-gray-800 text-gray-200 p-3 rounded-2xl rounded-tl-none shadow-md text-xs leading-relaxed">
            ${htmlContent}
        </div>
    `;
    container.appendChild(div);
    scrollToBottom();
}

function showTyping(state) {
    const typing = document.getElementById('chat-typing');
    if (typing) {
        if (state) typing.classList.remove('hidden');
        else typing.classList.add('hidden');
    }
}

function scrollToBottom() {
    const container = document.getElementById('chat-messages');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
}

function escapeHtml(text) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.replace(/[&<>"']/g, m => map[m]);
}

async function addChatProductToCart(productId, title) {
    showTyping(true);
    try {
        const response = await fetch('/sepet/ekle/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ quantity: 1 })
        });

        showTyping(false);
        if (response.ok) {
            const data = await response.json();
            const productTitle = title || 'Ürün';
            appendBotMessage(`🛒 <b>'${escapeHtml(productTitle)}'</b> sepetinize başarıyla eklendi!<br><br>👉 <a href='/sepet' class='text-yellow-400 font-bold underline bg-yellow-500/10 px-2 py-1 rounded border border-yellow-500/20 inline-block'>Sepetimi Görüntüle ve Öde</a>`);
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            if (typeof openSlideOverCart === 'function') openSlideOverCart();
        } else {
            const errData = await response.json().catch(() => ({}));
            appendBotMessage(`⚠️ ${errData.message || 'Ürün sepete eklenirken bir sorun oluştu.'}`);
        }
    } catch (err) {
        showTyping(false);
        appendBotMessage("⚠️ Sepete ekleme sırasında bağlantı hatası oluştu.");
    }
}
</script>
