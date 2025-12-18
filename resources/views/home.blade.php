@extends('layouts.app')

@section('content')
    <!-- Content -->
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">

                    <div class="divider">
                        <div class="divider-text">Welcome {{ Auth::user()->name ?? 'User' }}</div>
                    </div>


                    <div class="row">
                        <!-- Bootstrap carousel -->
                        <div class="col-md">
                            <h5 class="my-4">Wuthering Waves</h5>

                            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></li>
                                    <li data-bs-target="#carouselExample" data-bs-slide-to="1"></li>
                                    <li data-bs-target="#carouselExample" data-bs-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100" src="{{ asset('img/pro.jpg') }}" alt="First slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pe">Phrolova</h3>
                                            <p id="pe">"The cold never slows me. It simply reminds me to keep moving."</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="{{ asset('img/car.jpg') }}" alt="Second slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pe">Cartethya</h3>
                                            <p id="pe">"Silence isn’t peace—it’s where all unspoken thoughts hide."</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="{{ asset('img/camel.jpg') }}" alt="Third slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pe">Camellya</h3>
                                            <p id="pe">"I dance between softness and danger; that edge is where I truly breathe."
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExample" role="button"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExample" role="button"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                        <!-- Bootstrap crossfade carousel -->
                        <div class="col-md">
                            <h5 class="my-4">Genshin Impact</h5>

                            <div id="carouselExample-cf" class="carousel carousel-dark slide carousel-fade"
                                data-bs-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-bs-target="#carouselExample-cf" data-bs-slide-to="0" class="active"></li>
                                    <li data-bs-target="#carouselExample-cf" data-bs-slide-to="1"></li>
                                    <li data-bs-target="#carouselExample-cf" data-bs-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="d-block w-100" src="{{ asset('img/furin.jpg') }}" alt="First slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pi">Furina</h3>
                                            <p id="pi">"Even when the heart trembles, the show must remain flawless."</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="{{ asset('img/lumine.jpg') }}" alt="Second slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pi">Lumine</h3>
                                            <p id="pi">"I walk forward with no clear path, yet I trust the journey will lead me
                                                somewhere."</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="{{ asset('img/hutaw.jpg') }}" alt="Third slide" />
                                        <div class="carousel-caption d-none d-md-block">
                                            <h3 id="pi">Hu Tao</h3>
                                            <p id="pi">"Life and death are just two doors—so why not laugh while passing through?"
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExample-cf" role="button"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExample-cf" role="button"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
                <!-- / Content -->
            @endsection
