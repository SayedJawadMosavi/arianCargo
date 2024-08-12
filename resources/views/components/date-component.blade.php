<div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->

    @csrf
    @method('POST')
    <div class="form-row align-items-center my-5 offset-md-1">

        @if ($settings->date_type=='shamsi')
            <div class="col-xl-4 col-sm-4 col-12">
                <label for="validationServer01">{{ __('home.from_date') }}</label>
                <input type="text" class="form-control form-control " name="from_shamsi" autocomplete="off" id="dates" >

                @error('date')
                <div id="" class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="col-xl-4 col-sm-4 col-12 ">
                <label for="validationServer01">{{ __('home.to_date') }}</label>
                <input type="text" class="form-control form-control " name="to_shamsi" autocomplete="off" id="dates1" >

                @error('date')
                <div id="" class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        @else
            <div class="col-xl-4 col-sm-4 col-12 ">
                <label for="validationServer01">{{ __('home.from_date') }}</label>
                <input type="date" class="form-control " id="date" name="from_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                @error('date')
                <div id="" class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="col-xl-4 col-sm-4 col-12 ">
                <label for="validationServer01">{{ __('home.to_date') }}</label>
                <input type="date" class="form-control " id="date" name="to_miladi" autocomplete="off" value="{{ date('Y-m-d') }}">
                @error('date')
                <div id="" class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        @endif



        <div class="col-6 col-sm-2 ">
            <label class="" for="inlineFormInputGroup">   <br/><br/><br/></label>
            <button type="submit" class="btn btn btn-primary">  {{__('home.send')}}</button>
        </div>
    </div>

</div>
