@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xs">
            <a name="" id=""class="btn btn-primary m-2" href="{{route('todos.create')}}" role="button" >Ajouter une todo</a>
        </div>
        @if (Route::currentRouteName() == 'todos.index')

        <div class="col-xs">
            <a name="" id=""class="btn btn-warning m-2" href="{{route('todos.undone')}}" role="button" >Voire les todos ouvertes</a>
        </div>
        <div class="col-xs">
            <a name="" id=""class="btn btn-success m-2" href="{{route('todos.done')}}" role="button" >Voire les todos terminer</a>
        </div>
        @elseif(Route::currentRouteName() =='todos.done')
        <div class="col-xs">
            <a name="" id=""class="btn btn-dark m-2" href="{{route('todos.index')}}" role="button" >Voire toutes les todos</a>
        </div>
        <div class="col-xs">
            <a name="" id=""class="btn btn-warning m-2" href="{{route('todos.undone')}}" role="button" >Voire les todos ouvertes</a>
        </div>
        @elseif(Route::currentRouteName() == 'todos.undone')
        <div class="col-xs">
            <a name="" id=""class="btn btn-dark m-2" href="{{route('todos.index')}}" role="button" >Voire toutes les todos</a>
        </div>
        <div class="col-xs">
            <a name="" id=""class="btn btn-success m-2" href="{{route('todos.done')}}" role="button" >Voire les todos terminer</a>
        </div>
        @endif
    </div>
</div>

@foreach ($datas as $data)
<div class="alert alert-{{ $data->done ? 'success' : 'warning' }}" role="alert" >
    <div class="row">
        <div class="col-sm">
            <p class="my-0">
                <strong>
                    <span class="badge badge-dark">
                        #{{ $data->id }}
                    </span>
                </strong>
                 <small>
                     {{-- {{ dd($data) }} --}}

                    créée {{ $data->created_at->from() }} par
                     {{ Auth::user()->id == $data->user->id ? 'moi' : $data->user->name }}

                      @if ($data->todoaffectedTo && $data->todoaffectedTo->id == Auth::user()->id)

                    affecter à moi

                     @elseif($data->todoAffectedTo)

                    {{ $data->todoAffectedTo ? ', affecter à' . $data->todoAffectedTo->name :'' }}

                     @endif
                     {{-- display affected by someone or by user himself --}}

                      @if ($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id == Auth::user()->id )

                     Par moi-meme :D

                     @elseif($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id != Auth::user()->id )
                         par {{$data->todoAffectedBy->name}}
                     @endif
                </small>
                @if($data->done)
                <small>
                    <p>
                        Terminer
                        {{ $data->updated_at->from() }} - Terminer en
                        {{ $data->updated_at->diffForHumans($data->created_at, 1) }}

                    </p>
                </small>
                @endif
            </p>
            <details>
                <summary>
                    <strong>{{$data->name}} @if($data->done) <span class="badge badge-success">done</span>@endif</strong>
                </summary>
                <p>{{ $data->description }}</p>
            </details>
        </div>
        <div class="col-sm form-inline justify-content-end my-1">
            {{-- button affect to --}}
            <div class="dropdown open">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                            Affecter à
                        </button>
                <div class="dropdown-menu" aria-labelledby="triggerId">
                   @foreach ($users as $user)
                   <a class="dropdown-item" href="/todos/{{ $data->id }}/affectedTo/{{ $user->id }}">{{ $user->name }}</a>

                   @endforeach
                </div>
            </div>
            {{-- Button done / Button undone --}}
            @if ($data->done == 0)
            <form action="{{ Route('todos.makedone', $data->id) }}" method="post">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success mx-1" style="min-width: 90px"> Done </button>
            </form>
            @else
            <form action="{{ Route('todos.makeundone', $data->id) }}" method="post">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-warning mx-1" style="min-width: 90px">Undone</button>
            </form>
            @endif
            {{-- button Editer --}}
            @can('edit', $data)
            <a name="" id="" class="btn btn-info mx-1" href="{{ Route('todos.edit', $data->id) }}"
                 role="button">Editer</a>
            @elsecannot('edit', $data)
            <a name="" id="" class="btn btn-info mx-1 disabled" href="{{ Route('todos.edit', $data->id) }}"
                role="button">Editer</a>
            @endcan
            {{-- button Delete --}}
            @can('delete', $data)
            <form action="{{ Route('todos.destroy', $data->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mx-1">Effacer</button>
            </form>
            @elsecannot('delete', $data)
            <form>
                <button type="submit" class="btn btn-danger mx-1 disabled">Effacer</button>
            </form>
                @endcan

        </div>
    </div>
</div>
@endforeach
{{$datas->links()}}
@endsection
