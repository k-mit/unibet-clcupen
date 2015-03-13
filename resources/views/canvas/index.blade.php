@extends('app')

@section('content')
    <div id="main" class="container-fluid">
        <main class="row">
            <div class="col-sm-5 full-height">
                <div class="row comp-info">
                    <div class="col-sm-8">
                        <h1 class="cl-logo">CL-CUPEN</h1>
                    </div>
                </div>

            </div>
            <div class="col-sm-7 full-height">
                <div class="row tool-boxes">
                    <div class="col-sm-6 tool">
                        <ul class="row tabrow">
                            <li class="col-sm-4">
                                <a href="#">10 Tips</a>
                            </li>
                            <li class="col-sm-4 active">
                                <a href="#">Form</a>
                            </li>
                            <li class="col-sm-4">
                                <a href="#">Glenns Tips</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div class="col-sm-12 tabcontent">

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 tool">
                        <ul class="row tabrow">
                            <li class="col-sm-6">
                                <a href="#">Alla</a>
                            </li>
                            <li class="col-sm-6 active">
                                <a href="#">Vänner</a>
                            </li>
                        </ul>
                        <div class="row contentrow">
                            <div class="col-sm-12 tabcontent">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="row party-box glenn-bg">
                    <div class="col-sm-12">

                    </div>

                </div>

            </div>
        </main>
    </div>

@endsection