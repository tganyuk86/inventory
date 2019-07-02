@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Select a Floor Plan</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container">

                    @foreach($floors as $floor)
                      <div class="row">
                        <div class="col-sm">
                          {{$floor->name}}
                        </div>
                        <div class="col-sm">
                          <a href="/floor/{{$floor->id}}"><button>OPEN!</button></a>
                        </div>
                      </div>
                    @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
