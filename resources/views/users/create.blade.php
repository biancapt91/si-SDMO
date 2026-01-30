@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create User</h1>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-3 position-relative">
                <label>Name</label>
                <input id="user_name" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="off">
                <ul id="user_suggestions" class="list-group position-absolute" style="z-index:1050;display:none;width:100%"></ul>
            </div>

            <div class="mb-3">
                <label>NIP</label>
                <input id="user_nip" class="form-control" name="nip" value="{{ old('nip') }}" required>
                <div class="form-text">Isi NIP jika akun ini terkait dengan data pegawai</div>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input class="form-control" name="password" type="password" required>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input class="form-control" name="password_confirmation" type="password" required>
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button class="btn btn-primary">Create</button>
        </form>
    </div>

    <script>
        (function(){
            const input = document.getElementById('user_name');
            const hiddenNip = document.getElementById('user_nip');
            const list = document.getElementById('user_suggestions');
            let timeout = null;

            function clearList(){
                list.innerHTML = '';
                list.style.display = 'none';
            }

            function render(items){
                clearList();
                if (!items.length) return;
                items.forEach(it => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.textContent = it.nama + ' (' + it.nip + ')';
                    li.dataset.nip = it.nip;
                    li.dataset.nama = it.nama;
                    li.addEventListener('click', function(){
                        input.value = this.dataset.nama + ' (' + this.dataset.nip + ')';
                        hiddenNip.value = this.dataset.nip;
                        clearList();
                    });
                    list.appendChild(li);
                });
                list.style.display = 'block';
            }

            input.addEventListener('input', function(){
                const q = this.value.trim();
                if (timeout) clearTimeout(timeout);
                hiddenNip.value = '';
                if (q.length < 5) { clearList(); return; }
                timeout = setTimeout(function(){
                    fetch('{{ route('pegawai.suggest') }}?q=' + encodeURIComponent(q))
                        .then(r => r.json())
                        .then(data => render(data))
                        .catch(() => clearList());
                }, 250);
            });

            document.addEventListener('click', function(e){
                if (!list.contains(e.target) && e.target !== input) {
                    clearList();
                }
            });
        })();
    </script>
@endsection
