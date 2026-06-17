@extends('layouts.admin')

@section('title', 'Chat Pelanggan - Admin AstridMart')
@section('page_title', 'Obrolan Customer')

@section('content')
<div class="bg-white border border-slate-150 rounded-2xl overflow-hidden shadow-sm flex flex-col md:flex-row h-[520px]">
    
    <!-- Sidebar Contacts (Left) -->
    <div class="w-full md:w-80 border-r border-slate-150 flex flex-col bg-slate-50/50">
        <div class="p-4 border-b border-slate-150 font-bold text-xs uppercase text-slate-400 text-left">
            Daftar Percakapan
        </div>
        <!-- Search Contact Box -->
        <div class="p-3 border-b border-slate-150 bg-white">
            <div class="relative">
                <input type="text" id="contact-search-input" placeholder="Cari nama atau telepon..." class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 pl-3 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                <span class="material-icons absolute right-2.5 top-2.5 text-slate-400 text-sm">search</span>
            </div>
        </div>
        <div class="flex-grow overflow-y-auto">
            @forelse($contacts as $contact)
                @php
                    $isActive = $activeContact && $activeContact->id === $contact->id;
                @endphp
                <a href="{{ route('admin.chat', ['contact_id' => $contact->id]) }}" 
                   class="contact-item flex items-center gap-3 p-4 border-b border-slate-100 transition-colors text-left
                   {{ $isActive ? 'bg-emerald-50/40 border-l-4 border-emerald-600' : 'hover:bg-slate-50 bg-white' }}">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-grow">
                        <h4 class="contact-name font-bold text-xs text-slate-800 truncate">{{ $contact->name }}</h4>
                        <p class="contact-phone text-[9px] text-slate-400 mt-0.5 truncate">{{ $contact->phone_number }}</p>
                    </div>
                    @if($contact->unread_count > 0 && !$isActive)
                        <span class="w-5 h-5 rounded-full bg-rose-500 text-[10px] text-white flex items-center justify-center font-bold flex-shrink-0">{{ $contact->unread_count }}</span>
                    @endif
                </a>
            @empty
                <p class="text-xs text-slate-400 text-center py-8">Belum ada customer memulai chat.</p>
            @endforelse
        </div>
    </div>
    
    <!-- Chat Window (Right) -->
    <div class="flex-grow flex flex-col h-full bg-white relative">
        @if($activeContact)
            <!-- Window Header -->
            <div class="px-6 py-4 border-b border-slate-150 flex items-center gap-3 bg-slate-50/20">
                <div class="w-9 h-9 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($activeContact->name, 0, 1)) }}
                </div>
                <div class="text-left">
                    <h4 class="font-bold text-xs text-slate-850">{{ $activeContact->name }}</h4>
                    <p class="text-[9px] text-slate-400">Customer | Telepon: {{ $activeContact->phone_number }}</p>
                </div>
            </div>
            
            <!-- Conversation Area -->
            <div id="admin-chat-container" class="flex-grow p-6 overflow-y-auto overflow-x-hidden flex flex-col gap-4 bg-slate-50/30">
                @forelse($messages as $msg)
                    @php
                        $isAdminMsg = $msg->sender_id === Auth::user()->id;
                        $editedStr = $msg->updated_at != $msg->created_at ? ' <span class="text-[9px] opacity-60">(diedit)</span>' : '';
                    @endphp
                    <div class="msg-bubble-container flex {{ $isAdminMsg ? 'justify-end' : 'justify-start' }}" data-msg-id="{{ $msg->id }}">
                        <div class="group flex items-center max-w-[70%] {{ $isAdminMsg ? 'flex-row-reverse' : 'flex-row' }}" style="{{ $isAdminMsg ? 'flex-direction: row-reverse;' : '' }}">
                            <div class="relative msg-bubble-wrapper">
                                <div class="rounded-2xl p-4 shadow-sm text-xs text-left
                                    {{ $isAdminMsg 
                                        ? 'bg-emerald-600 text-white rounded-tr-none' 
                                        : 'bg-white border border-slate-150 text-slate-855 rounded-tl-none' }}">
                                    <p class="msg-text">{{ $msg->message_text }}</p>
                                    <p class="text-[8px] text-right mt-1.5 leading-none {{ $isAdminMsg ? 'text-emerald-200' : 'text-slate-400' }}">
                                        <span class="msg-edited">{!! $editedStr !!}</span> {{ $msg->created_at->format('H:i') }}
                                    </p>
                                </div>
                                @if($isAdminMsg)
                                    <div id="msg-menu-{{ $msg->id }}" class="hidden absolute bg-white border border-slate-200 rounded-xl shadow-lg p-1" style="position: absolute; right: 0; bottom: 100%; margin-bottom: 4px; width: 96px; z-index: 9999;">
                                        <button onclick="triggerEdit({{ $msg->id }}, '{{ addslashes($msg->message_text) }}')" class="flex items-center gap-1 w-full p-2 text-left text-[10px] font-semibold text-slate-700 hover:bg-slate-50 rounded-lg">
                                            <span class="material-icons text-xs">edit</span> Edit
                                        </button>
                                        <button onclick="deleteMessage({{ $msg->id }})" class="flex items-center gap-1 w-full p-2 text-left text-[10px] font-bold text-rose-600 hover:bg-rose-50 rounded-lg">
                                            <span class="material-icons text-xs text-rose-600">delete</span> Hapus
                                        </button>
                                    </div>
                                @endif
                            </div>
                            @if($isAdminMsg)
                                <div class="msg-menu-container mx-2">
                                    <button onclick="toggleMsgMenu(event, {{ $msg->id }})" class="p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-150 transition-colors" title="Menu">
                                        <span class="material-icons text-base leading-none">more_vert</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center my-auto">Obrolan kosong.</p>
                @endforelse
            </div>
            
            <!-- Input Form -->
            <div class="p-4 border-t border-slate-150 bg-white">
                <form id="admin-chat-form" class="flex items-center gap-3">
                    <input type="text" id="admin-chat-input" required autocomplete="off" placeholder="Ketik balasan Anda disini..." class="flex-grow bg-slate-100 border-none rounded-xl py-3 px-4 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 text-slate-800">
                    <button type="submit" class="w-11 h-11 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center transition-colors flex-shrink-0 shadow-lg shadow-emerald-600/10">
                        <span class="material-icons">send</span>
                    </button>
                </form>
            </div>
        @else
            <!-- Active Contact Not Selected -->
            <div class="my-auto text-center py-8">
                <span class="material-icons text-slate-300 text-6xl">forum</span>
                <h4 class="font-bold text-slate-700 mt-2 text-sm">Pilih Customer</h4>
                <p class="text-[10px] text-slate-400 max-w-xs mx-auto mt-1">Silakan pilih salah satu customer di daftar percakapan sebelah kiri untuk mulai membaca dan membalas pesan.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($activeContact)
<script>
    const contactId = {{ $activeContact->id }};
    const adminId = {{ Auth::user()->id }};
    const container = document.getElementById('admin-chat-container');
    const form = document.getElementById('admin-chat-form');
    const input = document.getElementById('admin-chat-input');
    
    function scrollBottom() {
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    
    scrollBottom();
    
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;
            
            input.disabled = true;
            const btn = form.querySelector('button[type="submit"]');
            const btnIcon = btn.querySelector('.material-icons');
            const originalIcon = btnIcon.textContent;
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btnIcon.textContent = 'hourglass_empty';
            
            fetch('/api/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    receiver_id: contactId,
                    message_text: text
                })
            })
            .then(res => res.json())
            .then(data => {
                input.disabled = false;
                btn.disabled = false;
                btn.style.opacity = '1';
                btnIcon.textContent = originalIcon;
                
                if (data.success) {
                    input.value = '';
                    pollAdminMessages();
                } else {
                    alert('Gagal mengirim pesan. Silakan coba lagi.');
                }
            })
            .catch(() => {
                input.disabled = false;
                btn.disabled = false;
                btn.style.opacity = '1';
                btnIcon.textContent = originalIcon;
                alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            });
        });
    }
    
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
        );
    }

    function escapeJSString(str) {
        return str.replace(/\\/g, '\\\\')
                  .replace(/'/g, "\\'")
                  .replace(/"/g, '\\"')
                  .replace(/\n/g, '\\n')
                  .replace(/\r/g, '\\r');
    }

    // Toggle menu
    function toggleMsgMenu(event, id) {
        event.stopPropagation();
        document.querySelectorAll('[id^="msg-menu-"]').forEach(menu => {
            if (menu.id !== `msg-menu-${id}`) {
                menu.classList.add('hidden');
                const wrapper = menu.closest('.msg-bubble-wrapper');
                if (wrapper) wrapper.style.zIndex = '';
            }
        });
        const menu = document.getElementById(`msg-menu-${id}`);
        if (menu) {
            const wrapper = menu.closest('.msg-bubble-wrapper');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                if (wrapper) wrapper.style.zIndex = '50';
            } else {
                menu.classList.add('hidden');
                if (wrapper) wrapper.style.zIndex = '';
            }
        }
    }

    // Trigger edit
    function triggerEdit(id, text) {
        const menu = document.getElementById(`msg-menu-${id}`);
        if (menu) {
            menu.classList.add('hidden');
            const wrapper = menu.closest('.msg-bubble-wrapper');
            if (wrapper) wrapper.style.zIndex = '';
        }
        editMessage(id, text);
    }

    // Close menus on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('[id^="msg-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
            const wrapper = menu.closest('.msg-bubble-wrapper');
            if (wrapper) wrapper.style.zIndex = '';
        });
    });

    // Edit message function
    function editMessage(id, oldText) {
        const bubble = container.querySelector(`[data-msg-id="${id}"] .msg-text`);
        if (!bubble) return;
        
        if (bubble.querySelector('input')) return;
        
        const originalHTML = bubble.innerHTML;
        bubble.innerHTML = `
            <div class="flex flex-col gap-1.5 mt-1 min-w-[200px]">
                <input type="text" class="edit-input w-full bg-slate-50 text-slate-800 text-xs border border-slate-300 rounded-lg py-1.5 px-2.5 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="${escapeHTML(oldText)}">
                <div class="flex justify-end gap-1">
                    <button class="cancel-btn text-[10px] bg-slate-150 hover:bg-slate-200 text-slate-700 px-2 py-0.5 rounded-md">Batal</button>
                    <button class="save-btn text-[10px] bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-0.5 rounded-md">Simpan</button>
                </div>
            </div>
        `;
        
        const input = bubble.querySelector('.edit-input');
        input.focus();
        input.select();
        
        const cancelBtn = bubble.querySelector('.cancel-btn');
        const saveBtn = bubble.querySelector('.save-btn');
        
        cancelBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            bubble.innerHTML = originalHTML;
        });
        
        saveBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const newText = input.value.trim();
            if (!newText) return;
            
            fetch(`/api/chat/edit/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message_text: newText
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    bubble.innerHTML = escapeHTML(newText);
                    pollAdminMessages();
                } else {
                    alert('Gagal mengedit pesan.');
                    bubble.innerHTML = originalHTML;
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi.');
                bubble.innerHTML = originalHTML;
            });
        });
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveBtn.click();
            } else if (e.key === 'Escape') {
                cancelBtn.click();
            }
        });
    }

    // Delete message function
    function deleteMessage(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) return;
        
        fetch(`/api/chat/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const el = container.querySelector(`[data-msg-id="${id}"]`);
                if (el) el.remove();
                pollAdminMessages();
            } else {
                alert('Gagal menghapus pesan.');
            }
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    }
    
    // Poll for new, updated and deleted messages (DOM-diffing)
    function pollAdminMessages() {
        fetch(`/api/chat/messages?other_id=${contactId}`)
            .then(res => res.json())
            .then(messages => {
                const empty = container.querySelector('.my-auto');
                if (empty && messages.length > 0) empty.remove();

                const fetchedIds = messages.map(m => m.id);

                // 1. Remove deleted messages from DOM
                const domMessages = container.querySelectorAll('.msg-bubble-container');
                domMessages.forEach(el => {
                    const id = parseInt(el.getAttribute('data-msg-id'));
                    if (!fetchedIds.includes(id)) {
                        el.remove();
                    }
                });

                // 2. Add or update messages
                let hasNewMessage = false;
                messages.forEach(msg => {
                    let el = container.querySelector(`[data-msg-id="${msg.id}"]`);
                    const isAdmin = msg.sender_id === adminId;
                    const dateObj = new Date(msg.created_at || Date.now());
                    const timeStr = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
                    const editedStr = msg.updated_at !== msg.created_at ? ' <span class="text-[9px] opacity-60">(diedit)</span>' : '';

                    if (el) {
                        if (el.querySelector('.edit-input')) return;
                        
                        const textEl = el.querySelector('.msg-text');
                        if (textEl && textEl.innerHTML !== escapeHTML(msg.message_text)) {
                            textEl.innerHTML = escapeHTML(msg.message_text);
                            let editedEl = el.querySelector('.msg-edited');
                            if (editedEl) editedEl.innerHTML = editedStr;
                            
                            const editBtn = el.querySelector('button[onclick^="triggerEdit"]');
                            if (editBtn) {
                                editBtn.setAttribute('onclick', `triggerEdit(${msg.id}, '${escapeJSString(msg.message_text)}')`);
                            }
                        }
                    } else {
                        // Append new message
                        const div = document.createElement('div');
                        div.className = 'msg-bubble-container flex ' + (isAdmin ? 'justify-end' : 'justify-start');
                        div.setAttribute('data-msg-id', msg.id);
                        
                        let actionsHtml = '';
                        let menuHtml = '';
                        if (isAdmin) {
                            actionsHtml = `
                                <div class="msg-menu-container mx-2">
                                    <button onclick="toggleMsgMenu(event, ${msg.id})" class="p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-150 transition-colors" title="Menu">
                                        <span class="material-icons text-base leading-none">more_vert</span>
                                    </button>
                                </div>
                            `;
                            menuHtml = `
                                <div id="msg-menu-${msg.id}" class="hidden absolute bg-white border border-slate-200 rounded-xl shadow-lg p-1" style="position: absolute; right: 0; bottom: 100%; margin-bottom: 4px; width: 96px; z-index: 9999;">
                                    <button onclick="triggerEdit(${msg.id}, '${escapeJSString(msg.message_text)}')" class="flex items-center gap-1 w-full p-2 text-left text-[10px] font-semibold text-slate-700 hover:bg-slate-50 rounded-lg">
                                        <span class="material-icons text-xs">edit</span> Edit
                                    </button>
                                    <button onclick="deleteMessage(${msg.id})" class="flex items-center gap-1 w-full p-2 text-left text-[10px] font-bold text-rose-600 hover:bg-rose-50 rounded-lg">
                                        <span class="material-icons text-xs text-rose-600">delete</span> Hapus
                                    </button>
                                </div>
                            `;
                        }

                        div.innerHTML = `
                            <div class="group flex items-center max-w-[70%] ${isAdmin ? 'flex-row-reverse' : 'flex-row'}" style="${isAdmin ? 'flex-direction: row-reverse;' : ''}">
                                <div class="relative msg-bubble-wrapper">
                                    <div class="rounded-2xl p-4 shadow-sm text-xs text-left
                                        ${isAdmin 
                                            ? 'bg-emerald-600 text-white rounded-tr-none' 
                                            : 'bg-white border border-slate-150 text-slate-855 rounded-tl-none'}">
                                        <p class="msg-text">${escapeHTML(msg.message_text)}</p>
                                        <p class="text-[8px] text-right mt-1.5 leading-none ${isAdmin ? 'text-emerald-250' : 'text-slate-400'}">
                                            <span class="msg-edited">${editedStr}</span> ${timeStr}
                                        </p>
                                    </div>
                                    ${menuHtml}
                                </div>
                                ${actionsHtml}
                            </div>
                        `;
                        container.appendChild(div);
                        hasNewMessage = true;
                    }
                });

                if (hasNewMessage) {
                    scrollBottom();
                }
            })
            .catch(err => console.error('Error polling messages:', err));
    }
    
    setInterval(pollAdminMessages, 2000);
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('contact-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                const contactItems = document.querySelectorAll('.contact-item');
                contactItems.forEach(item => {
                    const name = item.querySelector('.contact-name').textContent.toLowerCase();
                    const phone = item.querySelector('.contact-phone').textContent.toLowerCase();
                    if (name.includes(query) || phone.includes(query)) {
                        item.style.setProperty('display', 'flex', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        }

        function pollUnreadCounts() {
            fetch('/api/chat/unread-counts')
                .then(res => res.json())
                .then(data => {
                    const contactItems = document.querySelectorAll('.contact-item');
                    contactItems.forEach(item => {
                        const url = new URL(item.href, window.location.origin);
                        const id = url.searchParams.get('contact_id');
                        if (id) {
                            const count = data[id] || 0;
                            const isActive = item.classList.contains('border-emerald-600');
                            let badge = item.querySelector('.bg-rose-500');
                            
                            if (count > 0 && !isActive) {
                                if (badge) {
                                    badge.textContent = count;
                                } else {
                                    badge = document.createElement('span');
                                    badge.className = 'w-5 h-5 rounded-full bg-rose-500 text-[10px] text-white flex items-center justify-center font-bold flex-shrink-0';
                                    badge.textContent = count;
                                    item.appendChild(badge);
                                }
                            } else {
                                if (badge) {
                                    badge.remove();
                                }
                            }
                        }
                    });
                })
                .catch(err => console.error('Error polling unread counts:', err));
        }

        setInterval(pollUnreadCounts, 2000);
        pollUnreadCounts();
    });
</script>
@endsection
