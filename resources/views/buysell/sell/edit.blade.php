<x-app-layout>
    @section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="{{route('sellbuy.index')}}">Sell and Buy</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Edit</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Sell & Buy Edit</h6>
    </nav>
    @endsection
    @section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body px-3 pt-3 pb-3">
                        <div class="card card-plain">
                            <div class="card-body">
                                <form role="form" id="addbuysell" name="addbuysell" method="POST" action="{{ route('sellbuy.update', $buy_sell_result->getSell->id) }}">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" value="0" name="is_buy">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label class="control-label" for="exchange">Exchange</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="exchange" id="exchange" value="{{ $buy_sell_result->getSell->exchange }}" placeholder="Exchange" disabled>
                                                </div>
                                                @error('exchange') <p class="help-block"> {{$message}} </p>@enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label class="control-label" for="date">Date</label>
                                                <div class="col-sm-12">
                                                    <input type="date" class="form-control" name="date" id="date" value="{{ $buy_sell_result->getSell->date }}" placeholder="Date" disabled>
                                                </div>
                                                @error('date') <p class="help-block"> {{$message}} </p>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label class="control-label" for="quantity">Quantity</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="quantity" id="quantity" value="{{ $buy_sell_result->getSell->quantity }}" placeholder="Quantity">
                                                </div>
                                                @error('quantity') <p class="help-block"> {{$message}} </p>@enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label class="control-label" for="average">Buy Value</label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" name="average" id="average" value="{{ $buy_sell_result->getSell->average }}" placeholder="Buy Value">
                                                </div>
                                                @error('average') <p class="help-block"> {{$message}} </p>@enderror
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="text-center">
                                <a href="{{route('sellbuy.show',$buy_sell_result->getSell->id)}}" class="btn btn-sm btn-secondary w-15 mb-0">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary w-15 mb-0">Update</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    @endsection

    @push('footer_scripts')
    <script type="text/javascript">
        $("#addbuysell").validate({
            rules: {
                quantity: {
                    required: true,
                    number: true
                },
                average: {
                    required: true,
                    number: true
                },
            },
            messages: {
                quantity: {
                    required: "Please enter buy quantity.",
                    number: "Please enter only digits"
                },
                average: {
                    required: "Please enter buy value.",
                    number: "Please enter only digits"
                },
            },
            onfocusout: function(element) {
                this.element(element);
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>