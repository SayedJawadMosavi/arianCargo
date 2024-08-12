<div class="navbar navbar-collapse responsive-navbar p-0">
    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
        <div class="d-flex order-lg-2">
            <!-- <div class="dropdown d-lg-none d-flex">
                <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                    <i class="fe fe-search"></i>
                </a>
                <div class="dropdown-menu header-search dropdown-menu-start">
                    <div class="input-group w-100 p-2">
                        <input type="text" class="form-control" placeholder="Search....">
                        <div class="input-group-text    btn btn-primary">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="d-flex country">
                <a class="nav-link icon text-center" data-bs-target="#country-selector"
                    data-bs-toggle="modal">
                    <i class="fe fe-globe"></i><span
                        class="fs-16 ms-2 d-none d-xl-block">{{ __('home.language') }}</span>
                </a>
            </div>
            <!-- COUNTRY -->
            <div class="d-flex country">
                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                    <span class="dark-layout" id="myonoffswitch2"><i  id="myonoffswitch2" class="fe fe-moon"></i></span>
                    <span class="light-layout"  id="myonoffswitch1"><i  id="myonoffswitch1" class="fe fe-sun"></i></span>
                </a>
            </div>
            <!-- Theme-Layout -->
            <!-- <div class="dropdown  d-flex shopping-cart">
                <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                    <i class="fe fe-shopping-cart"></i><span class="badge bg-secondary header-badge">4</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="drop-heading border-bottom">
                        <div class="d-flex">
                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark"> My Shopping Cart</h6>
                            <div class="ms-auto">
                                <span class="badge bg-danger-transparent header-badge text-danger fs-14">Hurry Up!</span>
                            </div>
                        </div>
                    </div>
                    <div class="header-dropdown-list message-menu">
                        <div class="dropdown-item d-flex p-4">
                            <a href="cart.html" class="open-file"></a>
                            <span
                                class="avatar avatar-xl br-5 me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/pngs/4.jpg')}}"></span>
                            <div class="wd-50p">
                                <h5 class="mb-1">Flower Pot for Home Decor</h5>
                                <span>Status: <span class="text-success">In Stock</span></span>
                                <p class="fs-13 text-muted mb-0">Quantity: 01</p>
                            </div>
                            <div class="ms-auto text-end d-flex fs-16">
                                <span class="fs-16 text-dark d-none d-sm-block px-4">
                                    $438
                                </span>
                                <a href="javascript:void(0)" class="fs-16 btn p-0 cart-trash">
                                    <i class="fe fe-trash-2 border text-danger brround d-block p-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="dropdown-item d-flex p-4">
                            <a href="cart.html" class="open-file"></a>
                            <span
                                class="avatar avatar-xl br-5 me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/pngs/6.jpg')}}"></span>
                            <div class="wd-50p">
                                <h5 class="mb-1">Black Digital Camera</h5>
                                <span>Status: <span class="text-danger">Out Stock</span></span>
                                <p class="fs-13 text-muted mb-0">Quantity: 06</p>
                            </div>
                            <div class="ms-auto text-end d-flex">
                                <span class="fs-16 text-dark d-none d-sm-block px-4">
                                    $867
                                </span>
                                <a href="javascript:void(0)" class="fs-16 btn p-0 cart-trash">
                                    <i class="fe fe-trash-2 border text-danger brround d-block p-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="dropdown-item d-flex p-4">
                            <a href="cart.html" class="open-file"></a>
                            <span
                                class="avatar avatar-xl br-5 me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/pngs/8.jpg')}}"></span>
                            <div class="wd-50p">
                                <h5 class="mb-1">Stylish Rockerz 255 Ear Pods</h5>
                                <span>Status: <span class="text-success">In Stock</span></span>
                                <p class="fs-13 text-muted mb-0">Quantity: 05</p>
                            </div>
                            <div class="ms-auto text-end d-flex">
                                <span class="fs-16 text-dark d-none d-sm-block px-4">
                                    $323
                                </span>
                                <a href="javascript:void(0)" class="fs-16 btn p-0 cart-trash">
                                    <i class="fe fe-trash-2 border text-danger brround d-block p-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="dropdown-item d-flex p-4">
                            <a href="cart.html" class="open-file"></a>
                            <span
                                class="avatar avatar-xl br-5 me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/pngs/1.jpg')}}"></span>
                            <div class="wd-50p">
                                <h5 class="mb-1">Women Party Wear Dress</h5>
                                <span>Status: <span class="text-success">In Stock</span></span>
                                <p class="fs-13 text-muted mb-0">Quantity: 05</p>
                            </div>
                            <div class="ms-auto text-end d-flex">
                                <span class="fs-16 text-dark d-none d-sm-block px-4">
                                    $867
                                </span>
                                <a href="javascript:void(0)" class="fs-16 btn p-0 cart-trash">
                                    <i class="fe fe-trash-2 border text-danger brround d-block p-2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="dropdown-item d-flex p-4">
                            <a href="cart.html" class="open-file"></a>
                            <span
                                class="avatar avatar-xl br-5 me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/pngs/3.jpg')}}"></span>
                            <div class="wd-50p">
                                <h5 class="mb-1">Running Shoes for men</h5>
                                <span>Status: <span class="text-success">In Stock</span></span>
                                <p class="fs-13 text-muted mb-0">Quantity: 05</p>
                            </div>
                            <div class="ms-auto text-end d-flex">
                                <span class="fs-16 text-dark d-none d-sm-block px-4">
                                    $456
                                </span>
                                <a href="javascript:void(0)" class="fs-16 btn p-0 cart-trash">
                                    <i class="fe fe-trash-2 border text-danger brround d-block p-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <div class="dropdown-footer">
                        <a class="btn btn-primary btn-pill w-sm btn-sm py-2" href="checkout.html"><i class="fe fe-check-circle"></i> Checkout</a>
                        <span class="float-end p-2 fs-17 fw-semibold">Total: $6789</span>
                    </div>
                </div>
            </div> -->
            <!-- CART -->
            <div class="dropdown d-flex">
                <a class="nav-link icon full-screen-link nav-link-bg">
                    <i class="fe fe-minimize fullscreen-button"></i>
                </a>
            </div>
            <!-- FULL-SCREEN -->
            <!-- <div class="dropdown  d-flex notifications">
                <a class="nav-link icon" data-bs-toggle="dropdown"><i
                        class="fe fe-bell"></i><span class=" pulse"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="drop-heading border-bottom">
                        <div class="d-flex">
                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">Notifications
                            </h6>
                        </div>
                    </div>
                    <div class="notifications-menu">
                        <a class="dropdown-item d-flex" href="notify-list.html">
                            <div class="me-3 notifyimg  bg-primary brround box-shadow-primary">
                                <i class="fe fe-mail"></i>
                            </div>
                            <div class="mt-1 wd-80p">
                                <h5 class="notification-label mb-1">New Application received
                                </h5>
                                <span class="notification-subtext">3 days ago</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="notify-list.html">
                            <div class="me-3 notifyimg  bg-secondary brround box-shadow-secondary">
                                <i class="fe fe-check-circle"></i>
                            </div>
                            <div class="mt-1 wd-80p">
                                <h5 class="notification-label mb-1">Project has been
                                    approved</h5>
                                <span class="notification-subtext">2 hours ago</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="notify-list.html">
                            <div class="me-3 notifyimg  bg-success brround box-shadow-success">
                                <i class="fe fe-shopping-cart"></i>
                            </div>
                            <div class="mt-1 wd-80p">
                                <h5 class="notification-label mb-1">Your Product Delivered
                                </h5>
                                <span class="notification-subtext">30 min ago</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="notify-list.html">
                            <div class="me-3 notifyimg bg-pink brround box-shadow-pink">
                                <i class="fe fe-user-plus"></i>
                            </div>
                            <div class="mt-1 wd-80p">
                                <h5 class="notification-label mb-1">Friend Requests</h5>
                                <span class="notification-subtext">1 day ago</span>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a href="notify-list.html"
                        class="dropdown-item text-center p-3 text-muted">View all
                        Notification</a>
                </div>
            </div> -->
            <!-- NOTIFICATIONS -->
            <!-- <div class="dropdown  d-flex message">
                <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                    <i class="fe fe-message-square"></i><span class="pulse-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="drop-heading border-bottom">
                        <div class="d-flex">
                            <h6 class="mt-1 mb-0 fs-16 fw-semibold text-dark">You have 5
                                Messages</h6>
                            <div class="ms-auto">
                                <a href="javascript:void(0)" class="text-muted p-0 fs-12">make all unread</a>
                            </div>
                        </div>
                    </div>
                    <div class="message-menu message-menu-scroll">
                        <a class="dropdown-item d-flex" href="chat.html">
                            <span
                                class="avatar avatar-md brround me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/users/1.jpg')}}"></span>
                            <div class="wd-90p">
                                <div class="d-flex">
                                    <h5 class="mb-1">Peter Theil</h5>
                                    <small class="text-muted ms-auto text-end">
                                        6:45 am
                                    </small>
                                </div>
                                <span>Commented on file Guest list....</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="chat.html">
                            <span
                                class="avatar avatar-md brround me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/users/15.jpg')}}"></span>
                            <div class="wd-90p">
                                <div class="d-flex">
                                    <h5 class="mb-1">Abagael Luth</h5>
                                    <small class="text-muted ms-auto text-end">
                                        10:35 am
                                    </small>
                                </div>
                                <span>New Meetup Started......</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="chat.html">
                            <span
                                class="avatar avatar-md brround me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/users/12.jpg')}}"></span>
                            <div class="wd-90p">
                                <div class="d-flex">
                                    <h5 class="mb-1">Brizid Dawson</h5>
                                    <small class="text-muted ms-auto text-end">
                                        2:17 pm
                                    </small>
                                </div>
                                <span>Brizid is in the Warehouse...</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="chat.html">
                            <span
                                class="avatar avatar-md brround me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/users/4.jpg')}}"></span>
                            <div class="wd-90p">
                                <div class="d-flex">
                                    <h5 class="mb-1">Shannon Shaw</h5>
                                    <small class="text-muted ms-auto text-end">
                                        7:55 pm
                                    </small>
                                </div>
                                <span>New Product Realease......</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex" href="chat.html">
                            <span
                                class="avatar avatar-md brround me-3 align-self-center cover-image"
                                data-bs-image-src="{{asset('/back/images/users/3.jpg')}}"></span>
                            <div class="wd-90p">
                                <div class="d-flex">
                                    <h5 class="mb-1">Cherry Blossom</h5>
                                    <small class="text-muted ms-auto text-end">
                                        7:55 pm
                                    </small>
                                </div>
                                <span>You have appointment on......</span>
                            </div>
                        </a>

                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a href="javascript:void(0)" class="dropdown-item text-center p-3 text-muted">See all
                        Messages</a>
                </div>
            </div> -->
            <!-- MESSAGE-BOX -->
            <!-- <div class="dropdown d-flex header-settings">
                <a href="javascript:void(0);" class="nav-link icon"
                    data-bs-toggle="sidebar-right" data-target=".sidebar-right">
                    <i class="fe fe-align-right"></i>
                </a>
            </div> -->
            <!-- SIDE-MENU -->
            <div class="dropdown d-flex profile-1">
                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                    <img src="{{asset(auth()->user()->image)}}" alt="profile-user"
                        class="avatar  profile-user brround cover-image">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="drop-heading">
                        <div class="text-center">
                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{auth()->user()->name}}</h5>
                            <small class="text-muted">{{auth()->user()->roles->pluck('name')->first()}}</small>
                        </div>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a class="dropdown-item" href="{{route('profile.get')}}">
                        <i class="dropdown-icon fe fe-user"></i> {{ __('home.profile') }}
                    </a>
                    <a class="dropdown-item" href="{{route('logout')}}">
                        <i class="dropdown-icon fe fe-alert-circle"></i> {{ __('home.sign_out') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
