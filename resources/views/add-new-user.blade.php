@extends('layouts.app')

@section('title', 'Share Token')

@section('content')
    <style>
        .main-content { padding: 40px 60px; background: #f8f9fa; min-height: 100vh; margin-left: auto; margin-right: auto; max-width: 800px; }
        .page-header { margin-bottom: 24px; text-align: center; }
        .page-title { font-size: 28px; font-weight: 600; color: #2c3e50; margin: 0; display: inline-flex; align-items: center; gap: 10px; justify-content: center; }
        .card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 16px; }
        .row { display: flex; gap: 12px; align-items: center; }
        .row > * { flex: 1; }
        .btn { padding: 10px 16px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: #667eea; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .muted { color: #6c757d; font-size: 13px; }
        .pill { background:#eef2ff; color:#4f46e5; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; }
        input[type=text] { width: 100%; padding: 10px 12px; border:1px solid #e5e7eb; border-radius:10px; }
        ul { list-style: none; padding: 0; margin: 0; }
        li { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f1f3f4; }
    </style>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Share Token</h1>
            <div class="muted">Share read-only access to your data with up to 5 users for 3 hours.</div>
        </div>

        @if(session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; font-weight: 500;">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px 20px; margin-bottom: 25px; border-radius: 8px; font-weight: 500;">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="row">
                <button id="btn-generate" class="btn btn-primary">Share Token</button>
                <input id="token-output" type="text" placeholder="Generated token appears here" readonly>
                <button id="btn-toggle" class="btn btn-secondary">Show</button>
            </div>
            <div class="muted" style="margin-top:8px;">Token valid for 3 hours • Max 5 users</div>
        </div>

        <div class="card">
            <div class="row">
                <input id="token-input" type="text" placeholder="Paste token here">
                <button id="btn-paste" class="btn btn-secondary">Paste Token</button>
            </div>
            <div class="muted" style="margin-top:8px;">If you joined via a token, you cannot generate your own.</div>
        </div>

        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                <div style="font-weight:700;">Shared Users</div>
                <span id="uses-pill" class="pill">0 / 5 used</span>
            </div>
            <ul id="shared-list"></ul>
        </div>
    </div>

    <script>
        let TOKEN_HIDDEN = true;
        let TOKEN_PLAIN = '';

        async function fetchState() {
            const res = await fetch('/share/state', { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
            const data = await res.json();
            const output = document.getElementById('token-output');
            const usesPill = document.getElementById('uses-pill');
            const list = document.getElementById('shared-list');
            const genBtn = document.getElementById('btn-generate');
            const toggleBtn = document.getElementById('btn-toggle');

            list.innerHTML = '';

            if (data.activeToken) {
                TOKEN_PLAIN = data.activeToken.token;
                output.value = TOKEN_HIDDEN && TOKEN_PLAIN ? '*' : TOKEN_PLAIN;
                usesPill.textContent = (data.activeToken.uses || 0) + ' / ' + (data.activeToken.max_uses || 5) + ' used';
                toggleBtn.disabled = !TOKEN_PLAIN;
                toggleBtn.textContent = TOKEN_HIDDEN ? 'Show' : 'Hide';
            } else {
                TOKEN_PLAIN = '';
                output.value = '';
                usesPill.textContent = '0 / 5 used';
                toggleBtn.disabled = true;
                toggleBtn.textContent = 'Show';
            }

            if (data.sharedFrom) {
                genBtn.disabled = true;
                genBtn.title = 'Blocked: you joined via a shared token';
            } else {
                genBtn.disabled = false;
                genBtn.title = '';
            }

            (data.sharedUsers || []).forEach(function(entry){
                const li = document.createElement('li');
                const left = document.createElement('div');
                const right = document.createElement('div');
                left.textContent = (entry.shared_user?.name || 'User') + ' • ' + (entry.shared_user?.email || '');
                const btn = document.createElement('button');
                btn.className = 'btn btn-danger';
                btn.textContent = 'Revoke';
                btn.onclick = async function(){ await revoke(entry.shared_user_id); };
                right.appendChild(btn);
                li.appendChild(left); li.appendChild(right);
                list.appendChild(li);
            });
        }

        async function generate() {
            const res = await fetch('/share/generate', { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
            if (res.ok) {
                const data = await res.json();
                TOKEN_PLAIN = data.token || '';
                TOKEN_HIDDEN = true;
                document.getElementById('token-output').value = TOKEN_PLAIN ? '*' : '';
                document.getElementById('btn-toggle').disabled = !TOKEN_PLAIN;
                document.getElementById('btn-toggle').textContent = 'Show';
                await Swal.fire({ icon: 'success', title: 'Token Generated', text: TOKEN_PLAIN, confirmButtonText: 'Copy', showCancelButton: true });
                navigator.clipboard?.writeText(data.token).catch(()=>{});
                await fetchState();
            } else {
                const err = await res.json().catch(()=>({message:'Failed'}));
                Swal.fire({ icon: 'error', title: 'Cannot Generate', text: err.message || 'Unknown error' });
            }
        }

        async function paste() {
            const token = document.getElementById('token-input').value.trim();
            if (!token) { return Swal.fire({ icon:'warning', title:'No token', text:'Please paste a token' }); }
            const res = await fetch('/share/paste', { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ token }) });
            if (res.ok) {
                Swal.fire({ icon: 'success', title: 'Access Granted' });
                document.getElementById('token-input').value = '';
                await fetchState();
            } else {
                const err = await res.json().catch(()=>({message:'Failed'}));
                Swal.fire({ icon: 'error', title: 'Unable to Join', text: err.message || 'Unknown error' });
            }
        }

        async function revoke(sharedUserId) {
            const ok = await Swal.fire({ title:'Revoke access?', icon:'warning', showCancelButton:true, confirmButtonText:'Revoke' }).then(r=>r.isConfirmed);
            if (!ok) return;
            const res = await fetch('/share/revoke/' + sharedUserId, { method: 'POST', credentials: 'same-origin', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
            if (res.ok) {
                Swal.fire({ icon:'success', title:'Access Revoked' });
                await fetchState();
            } else {
                const err = await res.json().catch(()=>({message:'Failed'}));
                Swal.fire({ icon:'error', title:'Failed', text: err.message || 'Unknown error' });
            }
        }

        function toggleVisibility() {
            if (!TOKEN_PLAIN) return;
            TOKEN_HIDDEN = !TOKEN_HIDDEN;
            document.getElementById('token-output').value = TOKEN_HIDDEN ? '*' : TOKEN_PLAIN;
            document.getElementById('btn-toggle').textContent = TOKEN_HIDDEN ? 'Show' : 'Hide';
        }

        document.getElementById('btn-generate').addEventListener('click', generate);
        document.getElementById('btn-toggle').addEventListener('click', toggleVisibility);
        document.getElementById('btn-paste').addEventListener('click', paste);
        document.addEventListener('DOMContentLoaded', fetchState);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection