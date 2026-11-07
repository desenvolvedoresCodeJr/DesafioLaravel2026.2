<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Meus dados
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Atualize suas informações.
        </p>
    </header>

    @unless ($user->is_admin)
        <div class="mt-4 rounded-lg bg-indigo-50 border border-[#111fb4] px-4 py-3">
            <span class="text-sm text-gray-600">Saldo disponível</span>
            <p class="text-2xl font-bold text-[#111fb4]">R$ {{ number_format($user->saldo, 2, ',', '.') }}</p>
        </div>
    @endunless

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
          x-data="{
            preview: null, cepErro: '',
            cep: @js(old('cep', $user->cep)),
            logradouro: @js(old('logradouro', $user->logradouro)),
            bairro: @js(old('bairro', $user->bairro)),
            cidade: @js(old('cidade', $user->cidade)),
            estado: @js(old('estado', $user->estado)),
            async buscarCep() {
                this.cepErro = '';
                const d = this.cep.replace(/\D/g, '');
                if (d.length !== 8) return;
                try {
                    const r = await fetch('/api/cep/' + d);
                    const j = await r.json();
                    if (!r.ok) { this.cepErro = j.erro; return; }
                    this.logradouro = j.logradouro; this.bairro = j.bairro;
                    this.cidade = j.localidade; this.estado = j.estado;
                } catch (e) { this.cepErro = 'Não foi possível consultar o CEP.'; }
            }
          }"
          class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="cep" value="CEP" />
            <x-text-input id="cep" name="cep" type="text" maxlength="9" class="mt-1 block w-full" x-model="cep" @blur="buscarCep()" required />
            <p x-show="cepErro" x-text="cepErro" class="mt-1 text-sm text-red-600"></p>
            <x-input-error class="mt-2" :messages="$errors->get('cep')" />
        </div>

        <div>
            <x-input-label for="logradouro" value="Logradouro" />
            <x-text-input id="logradouro" name="logradouro" type="text" class="mt-1 block w-full" x-model="logradouro" required />
            <x-input-error class="mt-2" :messages="$errors->get('logradouro')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="numero" value="Número" />
                <x-text-input id="numero" name="numero" type="text" class="mt-1 block w-full" :value="old('numero', $user->numero)" required />
                <x-input-error class="mt-2" :messages="$errors->get('numero')" />
            </div>
            <div>
                <x-input-label for="complemento" value="Complemento (opcional)" />
                <x-text-input id="complemento" name="complemento" type="text" class="mt-1 block w-full" :value="old('complemento', $user->complemento)" />
                <x-input-error class="mt-2" :messages="$errors->get('complemento')" />
            </div>
        </div>

        <div>
            <x-input-label for="bairro" value="Bairro" />
            <x-text-input id="bairro" name="bairro" type="text" class="mt-1 block w-full" x-model="bairro" required />
            <x-input-error class="mt-2" :messages="$errors->get('bairro')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="cidade" value="Cidade" />
                <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" x-model="cidade" required />
                <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
            </div>
            <div>
                <x-input-label for="estado" value="Estado" />
                <x-text-input id="estado" name="estado" type="text" class="mt-1 block w-full" x-model="estado" required />
                <x-input-error class="mt-2" :messages="$errors->get('estado')" />
            </div>
        </div>

        <div>
            <x-input-label for="telefone" value="Telefone" />
            <x-text-input id="telefone" name="telefone" type="text" class="mt-1 block w-full" :value="old('telefone', $user->telefone)" required />
            <x-input-error class="mt-2" :messages="$errors->get('telefone')" />
        </div>

        <div>
            <x-input-label for="data_nascimento" value="Data de nascimento" />
            <x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento', $user->data_nascimento?->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('data_nascimento')" />
        </div>

        <div>
            <x-input-label for="cpf" value="CPF" />
            <x-text-input id="cpf" name="cpf" type="text" maxlength="14" class="mt-1 block w-full" :value="old('cpf', $user->cpf)" required />
            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
        </div>

        <div>
            <x-input-label value="Foto de perfil (opcional)" />
            <label class="mt-1 block w-36 h-36 cursor-pointer border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center">
                <template x-if="preview"><img :src="preview" class="w-full h-full object-cover"></template>
                <template x-if="!preview">
                    @if ($user->foto_url)
                        <img src="{{ $user->foto_url }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-sm px-2 text-center">Adicione sua foto</span>
                    @endif
                </template>
                <input type="file" name="foto" accept="image/*" class="hidden"
                       @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
            </label>
            <x-input-error class="mt-2" :messages="$errors->get('foto')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">Salvo.</p>
            @endif
        </div>
    </form>
</section>
