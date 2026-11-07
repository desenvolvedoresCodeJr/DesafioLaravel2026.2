<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold">Gerenciamento de Usuários</h1>

                <button type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'criar-usuario')"
                    class="ml-auto bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg px-8 py-3">
                    + Criar Usuário
                </button>
            </div>

            @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">{{ session('status') }}</div>
            @endif
            @if (session('erro'))
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">{{ session('erro') }}</div>
            @endif
            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="border border-[#111fb4] rounded-2xl overflow-hidden">
                <table class="w-full text-center">
                    <thead>
                        <tr class="border-b border-[#111fb4] text-lg">
                            <th class="py-5">Nome</th>
                            <th class="py-5">Email</th>
                            <th class="py-5">Telefone</th>
                            <th class="py-5">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr>
                            <td class="py-5 text-lg">{{ $usuario->name }}</td>
                            <td class="py-5 text-lg">{{ $usuario->email }}</td>
                            <td class="py-5 text-lg">{{ $usuario->telefone }}</td>
                            <td class="py-5">
                                <div class="flex justify-center gap-2">
                                    <button type="button" title="Ver" x-data=""
                                        x-on:click="
                                                $dispatch('ver-usuario', @js([
                                                    'name'            => $usuario->name,
                                                    'email'           => $usuario->email,
                                                    'cep'             => $usuario->cep,
                                                    'logradouro'      => $usuario->logradouro,
                                                    'numero'          => $usuario->numero,
                                                    'complemento'     => $usuario->complemento,
                                                    'bairro'          => $usuario->bairro,
                                                    'cidade'          => $usuario->cidade,
                                                    'estado'          => $usuario->estado,
                                                    'telefone'        => $usuario->telefone,
                                                    'data_nascimento' => $usuario->data_nascimento?->format('d/m/Y'),
                                                    'cpf'             => $usuario->cpf,
                                                    'saldo'           => 'R$ ' . number_format($usuario->saldo, 2, ',', '.'),
                                                    'foto'            => $usuario->foto_url,
                                                ]));
                                                $dispatch('open-modal', 'ver-usuario');
                                            "
                                        class="w-7 h-7 border border-[#111fb4] text-[#111fb4] rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Editar" x-data=""
                                        x-on:click="
                                                $dispatch('editar-usuario', @js([
                                                    'action'          => route('usuarios.update', $usuario),
                                                    'name'            => $usuario->name,
                                                    'email'           => $usuario->email,
                                                    'cep'             => $usuario->cep,
                                                    'logradouro'      => $usuario->logradouro,
                                                    'numero'          => $usuario->numero,
                                                    'complemento'     => $usuario->complemento,
                                                    'bairro'          => $usuario->bairro,
                                                    'cidade'          => $usuario->cidade,
                                                    'estado'          => $usuario->estado,
                                                    'telefone'        => $usuario->telefone,
                                                    'data_nascimento' => $usuario->data_nascimento?->format('Y-m-d'),
                                                    'cpf'             => $usuario->cpf,
                                                    'saldo'           => $usuario->saldo,
                                                    'foto'            => $usuario->foto_url,
                                                ]));
                                                $dispatch('open-modal', 'editar-usuario');
                                            "
                                        class="w-7 h-7 border border-[#111fb4] text-[#111fb4] rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M16 3l5 5-9 9H7v-5l9-9z" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Excluir" x-data=""
                                        x-on:click="
                                                $dispatch('deletar-usuario', @js(['action' => route('usuarios.destroy', $usuario)]));
                                                $dispatch('open-modal', 'deletar-usuario');
                                            "
                                        class="w-7 h-7 border border-red-400 bg-red-200 text-red-700 rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V4h6v3m-8 0l1 13h8l1-13" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Enviar e-mail" x-data=""
                                        x-on:click="
                                            $dispatch('enviar-email', @js(['action' => route('usuarios.email', $usuario), 'nome' => $usuario->name, 'email' => $usuario->email]));
                                            $dispatch('open-modal', 'enviar-email');
                                        "
                                        class="w-7 h-7 border border-[#111fb4] text-[#111fb4] rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-16 text-gray-500">Nenhum usuário encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $usuarios->links() }}
            </div>

        </div>
    </div>

    {{-- Modal Criar --}}
    <x-modal name="criar-usuario" maxWidth="lg" :show="old('_form') === 'criar'">
        <div class="border-2 border-[#111fb4] rounded-lg overflow-hidden"
            x-data="{
                preview: null,
                cep: @js(old('cep', '')),
                logradouro: @js(old('logradouro', '')),
                bairro: @js(old('bairro', '')),
                cidade: @js(old('cidade', '')),
                estado: @js(old('estado', '')),
                cepErro: '',
                async buscarCep() {
                    this.cepErro = '';
                    const digitos = this.cep.replace(/\D/g, '');
                    if (digitos.length !== 8) return;
                    try {
                        const r = await fetch('/api/cep/' + digitos);
                        const j = await r.json();
                        if (!r.ok) { this.cepErro = j.erro; return; }
                        this.logradouro = j.logradouro;
                        this.bairro = j.bairro;
                        this.cidade = j.localidade;
                        this.estado = j.estado;
                    } catch (e) {
                        this.cepErro = 'Não foi possível consultar o CEP.';
                    }
                }
             }">

            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Criar Usuário</h2>
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" class="p-6 max-h-[75vh] overflow-y-auto">
                @csrf
                <input type="hidden" name="_form" value="criar">

                <label class="block text-sm">Nome</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Senha</label>
                <input type="password" name="password" required minlength="8" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">CEP</label>
                <input type="text" name="cep" x-model="cep" @blur="buscarCep()" maxlength="9" required
                    class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                <p x-show="cepErro" x-text="cepErro" class="mt-1 text-sm text-red-600"></p>

                <label class="block mt-4 text-sm">Logradouro</label>
                <input type="text" name="logradouro" x-model="logradouro" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Número</label>
                <input type="text" name="numero" value="{{ old('numero') }}" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Complemento (opcional)</label>
                <input type="text" name="complemento" value="{{ old('complemento') }}" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Bairro</label>
                <input type="text" name="bairro" x-model="bairro" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Cidade</label>
                <input type="text" name="cidade" x-model="cidade" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Estado</label>
                <input type="text" name="estado" x-model="estado" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone') }}" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Data de nascimento</label>
                <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">CPF</label>
                <input type="text" name="cpf" value="{{ old('cpf') }}" maxlength="14" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Saldo inicial</label>
                <input type="number" step="0.01" min="0" name="saldo" value="{{ old('saldo', 0) }}" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-6 mx-auto w-44 h-36 cursor-pointer border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center text-center">
                    <template x-if="!preview"><span class="text-gray-400 text-sm px-2">Adicione sua foto de perfil</span></template>
                    <template x-if="preview"><img :src="preview" class="w-full h-full object-cover"></template>
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </label>

                <div class="mt-6 flex justify-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')" class="w-40 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="w-40 bg-green-600 hover:bg-green-700 text-white rounded-lg py-2 text-lg">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Modal Visualizar --}}
    <x-modal name="ver-usuario" maxWidth="lg">
        <div x-data="{ p: {} }" @ver-usuario.window="p = $event.detail"
            class="border-2 border-[#111fb4] rounded-lg overflow-hidden">
            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Visualizar Usuário</h2>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <template x-for="campo in [
                ['Nome','name'],['E-mail','email'],['CEP','cep'],['Logradouro','logradouro'],
                ['Número','numero'],['Complemento','complemento'],['Bairro','bairro'],['Cidade','cidade'],
                ['Estado','estado'],['Telefone','telefone'],['Data de nascimento','data_nascimento'],
                ['CPF','cpf'],['Saldo','saldo']
            ]" :key="campo[1]">
                    <div class="mb-4">
                        <label class="block text-sm" x-text="campo[0]"></label>
                        <input type="text" readonly :value="p[campo[1]]"
                            class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">
                    </div>
                </template>

                <label class="block text-sm">Senha</label>
                <input type="text" readonly value="•••••" class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">

                <div class="mx-auto mt-6 w-36 h-36 border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center">
                    <template x-if="p.foto"><img :src="p.foto" class="w-full h-full object-cover"></template>
                </div>

                <div class="mt-6 flex justify-center">
                    <button type="button" x-on:click="$dispatch('close')" class="w-48 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">Fechar</button>
                </div>
            </div>
        </div>
    </x-modal>

    {{-- Modal Editar --}}
    <x-modal name="editar-usuario" maxWidth="lg">
        <div class="border-2 border-[#111fb4] rounded-lg overflow-hidden"
            x-data="{
            p: {}, preview: null, cepErro: '',
            async buscarCep() {
                this.cepErro = '';
                const d = (this.p.cep || '').replace(/\D/g, '');
                if (d.length !== 8) return;
                try {
                    const r = await fetch('/api/cep/' + d);
                    const j = await r.json();
                    if (!r.ok) { this.cepErro = j.erro; return; }
                    this.p.logradouro = j.logradouro; this.p.bairro = j.bairro;
                    this.p.cidade = j.localidade; this.p.estado = j.estado;
                } catch (e) { this.cepErro = 'Não foi possível consultar o CEP.'; }
            }
         }"
            @editar-usuario.window="p = $event.detail; preview = null; cepErro = ''">

            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Editar Usuário</h2>
            </div>

            <form method="POST" :action="p.action" enctype="multipart/form-data" class="p-6 max-h-[75vh] overflow-y-auto">
                @csrf
                @method('PUT')

                <label class="block text-sm">Nome</label>
                <input type="text" name="name" x-model="p.name" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">E-mail</label>
                <input type="email" name="email" x-model="p.email" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Senha (deixe em branco para manter)</label>
                <input type="password" name="password" minlength="8" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">CEP</label>
                <input type="text" name="cep" x-model="p.cep" @blur="buscarCep()" maxlength="9" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                <p x-show="cepErro" x-text="cepErro" class="mt-1 text-sm text-red-600"></p>

                <label class="block mt-4 text-sm">Logradouro</label>
                <input type="text" name="logradouro" x-model="p.logradouro" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Número</label>
                <input type="text" name="numero" x-model="p.numero" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Complemento (opcional)</label>
                <input type="text" name="complemento" x-model="p.complemento" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Bairro</label>
                <input type="text" name="bairro" x-model="p.bairro" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Cidade</label>
                <input type="text" name="cidade" x-model="p.cidade" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Estado</label>
                <input type="text" name="estado" x-model="p.estado" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Telefone</label>
                <input type="text" name="telefone" x-model="p.telefone" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Data de nascimento</label>
                <input type="date" name="data_nascimento" x-model="p.data_nascimento" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">CPF</label>
                <input type="text" name="cpf" x-model="p.cpf" maxlength="14" required class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Saldo</label>
                <input type="number" step="0.01" min="0" name="saldo" x-model="p.saldo" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-6 mx-auto w-36 h-36 cursor-pointer border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center">
                    <template x-if="preview || p.foto"><img :src="preview || p.foto" class="w-full h-full object-cover"></template>
                    <template x-if="!preview && !p.foto"><span class="text-gray-400 text-sm px-2 text-center">Foto de perfil</span></template>
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </label>

                <div class="mt-6 flex justify-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')" class="w-40 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">Cancelar</button>
                    <button type="submit" class="w-40 bg-green-600 hover:bg-green-700 text-white rounded-lg py-2 text-lg">Salvar</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Modal Deletar --}}
    <x-modal name="deletar-usuario" maxWidth="md">
        <div x-data="{ p: {} }" @deletar-usuario.window="p = $event.detail"
            class="border-2 border-[#111fb4] rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold">Deletar Usuário</h2>

            <svg class="w-16 h-16 mx-auto mt-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>

            <p class="mt-6 text-sm font-semibold">Você tem certeza que deseja deletar este usuário?</p>
            <p class="text-sm font-semibold">Essa ação não poderá ser desfeita.</p>

            <form method="POST" :action="p.action" class="mt-10 flex justify-center gap-6">
                @csrf
                @method('DELETE')
                <button type="button" x-on:click="$dispatch('close')" class="w-44 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                    Não, mantê-lo.
                </button>
                <button type="submit" class="w-44 bg-red-500 hover:bg-red-600 text-white rounded-lg py-2 text-lg">
                    Sim, deletar!
                </button>
            </form>
        </div>
    </x-modal>

    {{-- Modal email --}}
    <x-modal name="enviar-email" maxWidth="lg">
        <div x-data="{ p: {} }" @enviar-email.window="p = $event.detail"
            class="border-2 border-[#111fb4] rounded-lg overflow-hidden">
            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Enviar E-mail</h2>
            </div>

            <form method="POST" :action="p.action" class="p-6">
                @csrf
                <p class="text-sm">Para: <strong x-text="p.nome"></strong> (<span x-text="p.email"></span>)</p>

                <label class="block mt-4 text-sm">Assunto</label>
                <input type="text" name="assunto" required maxlength="150" class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Conteúdo do e-mail</label>
                <textarea name="conteudo" rows="6" required class="w-full border border-[#111fb4] rounded-2xl px-4 py-2"></textarea>

                <div class="mt-6 flex justify-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')" class="w-40 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">Cancelar</button>
                    <button type="submit" class="w-40 bg-green-600 hover:bg-green-700 text-white rounded-lg py-2 text-lg">Enviar</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>