@extends('gotime::layouts.' . config('naykel.template'))

@section('top-a')
    <div class="pxy-4 red tac bdr5">Top-A</div>
@endsection

@section('top-b')
    <div class="pxy-4 yellow tac bdr5">Top-B</div>
@endsection


@section('content')

<div class="pxy-4 blue tac bdr5">Main Content</div>

@endsection

@section('bottom-a')
    <div class="pxy-4 yellow tac bdr5">Bottom-A</div>
@endsection

@section('bottom-b')
    <div class="pxy-4 red tac bdr5">Bottom-B</div>
@endsection

@section('bottom-a')
<div class="dark">
    <div class="container">
        <div class="row tac">
            <div class="col-md-33">
                <div class="bx">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam culpa minima veritatis numquam earum esse fugiat rerum aut in quisquam.
                </div>
            </div>
            <div class="col-md-33">
                <div class="bx">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam culpa minima veritatis numquam earum esse fugiat rerum aut in quisquam.
                </div>
            </div>
            <div class="col-md-33">
                <div class="bx">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam culpa minima veritatis numquam earum esse fugiat rerum aut in quisquam.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


