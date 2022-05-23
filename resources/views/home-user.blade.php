<x-app-layout>
<div class="mt-4">
    <h2>Home Usuário</h2>
</div>

@if ($user->brand_license == TRUE)
    <a href="{{ route('brand.index') }}">
        <button type="button" class="btn btn-secondary">
            {{ __('Marcas') }}
        </button>
    </a>
@endif

@if ($user->category_license == TRUE)
    <a href="{{ route('category.index') }}">
        <button type="button" class="btn btn-secondary">
            {{ __('Categorias') }}
        </button>
    </a>
@endif

@if ($user->product_license == TRUE)
    <a href="{{ route('product.index') }}">
        <button type="button" class="btn btn-secondary">
            {{ __('Produtos') }}
        </button>
    </a>
@endif

@if ($user->product_license == FALSE AND $user->brand_license == FALSE AND $user->category_license == FALSE)
    {{ __('Nenhuma permissão concedida, aguarde liberação do administrador.') }}
@endif

<form method="POST" action="{{ route('logout') }}">
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            {{ __('Logout') }}
        </button>
    </div>
</form>
</x-app-layout>
