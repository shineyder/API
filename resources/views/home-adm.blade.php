<x-app-layout>
<div class="mt-4">
    <h2>Home Administrador</h2>
</div>

<form method="POST" action="{{ route('logout') }}">
    <div class="offset-md-11">
        <button type="submit" class="btn btn-primary">
            {{ __('Logout') }}
        </button>
    </div>
</form>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    Todos os Usuários
                </div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th colspan='4'>Permissões</th>
                                <th>Ações</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Adm</th>
                                <th>Marca</th>
                                <th>Categoria</th>
                                <th>Produtos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @if ($user->access == 'admin')
                                            <i class="fas fa-check"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->brand_license == TRUE)
                                            <i class="fas fa-check"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->category_license == TRUE)
                                            <i class="fas fa-check"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->product_license == TRUE)
                                            <i class="fas fa-check"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->access !== 'admin')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1{{$user->id}}">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal2{{$user->id}}">
                                                Deletar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($users as $user)
    <!-- Modal Edit-->
    <div class="modal fade" id="exampleModal1{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Permissões</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.update')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">

                        <div>
                            <input class="form-check-input" type="checkbox" value="1" name="brand_license" id="brand_license" @if($user->brand_license) checked @endif>
                            <label class="form-check-label" for="brand_license">
                                Marcas
                            </label>
                        </div>
                        <div>
                            <input class="form-check-input" type="checkbox" value="1" name="category_license" id="category_license" @if($user->category_license) checked @endif>
                            <label class="form-check-label" for="category_license">
                                Categorias
                            </label>
                        </div>
                        <div>
                            <input class="form-check-input" type="checkbox" value="1" name="product_license" id="product_license" @if($user->product_license) checked @endif>
                            <label class="form-check-label" for="product_license">
                                Produtos
                            </label>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deletar -->
    <div class="modal fade" id="exampleModal2{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Deletar Usuário</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja deletar esse usuário?</p>

                    <form action="{{route('user.delete')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-danger">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
</x-app-layout>
