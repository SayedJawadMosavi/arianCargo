<div class="row m-2">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
        @if (session()->has('success'))

            <div class="alert alert-success alert-dismissible fade show">
            <strong>{{session('success')}}</strong>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>
        @elseif (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
        <strong>{{session('error')}}</strong>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>
            </div>

        @endif
    </div>
</div>
