<x-app-layout>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 mt-4">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('auth/login')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        @if (Route::has('register.index'))
                            <a href="{{ route('register.index') }}">
                                <button type="button" class="btn btn-secondary">
                                    {{ __('Registrar') }}
                                </button>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
