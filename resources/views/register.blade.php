<x-app-layout>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-4">
            <div class="card">
                <div class="card-header">
                    Registrar Usuário
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('register.register')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="name" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="{{ route('login') }}">
                            <button type="button" class="btn btn-secondary">
                                {{ __('Cancelar') }}
                            </button>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
