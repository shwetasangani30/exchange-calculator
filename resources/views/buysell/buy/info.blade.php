<x-app-layout>
    @section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="{{route('buysell.index')}}">Buy and Sell</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Info</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Buy & Sell Info</h6>
    </nav>
    @endsection
    @section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong> {{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong> {{session('error')}}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card mb-4">
                    <div class="card-body px-3 pt-3 pb-3">
                        <input type="hidden" name="sell_info" id="sell_info" value="{{ $buy_sell_result->getBuySell->is_buy }}">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <label>Total Buy Quantity : </label> {{ number_format($buy_sell_result->total_quantity, 2) }} <br />
                                <label>Total Buy Average : </label> {{ number_format($buy_sell_result->total_average, 2) }} <br />
                                <label>Total Buy Value : </label> {{ number_format($buy_sell_result->total_value, 2) }} <br />
                                <label> Close Value : </label> {{ $buy_sell_result->close }}
                            </div>
                            <div class="col-lg-6 col-md-6">

                                <label> Difference : </label> {{ $buy_sell_result->difference }} <br />
                                <label> Profit & Loss : </label> {{ $buy_sell_result->profit_loss }} <br />
                                <label> Profit & Loss % : </label> {{ $buy_sell_result->profit_loss_per }} <br />
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive p-0">
                            <table id="buysell" class="table align-items-center mb-0 display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">#</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Exchange</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Quantity</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Average</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Total</th>
                                        <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @if (count($buy_sell))
                                    @foreach ($buy_sell as $buysell)
                                    <tr>
                                        <td class="text-sm">{{ $i++ }}</td>
                                        <td class="text-sm">{{ $buysell->exchange }}</td>
                                        <td class="text-sm">{{ $buysell->date }}</td>
                                        <td class="text-sm">{{ number_format($buysell->quantity,2) }}</td>
                                        <td class="text-sm">{{ number_format($buysell->average,2) }}</td>
                                        <td class="text-sm">{{ number_format($buysell->value,2) }}</td>
                                        <td class="text-sm">
                                            <form action="{{ route('buysell.destroy',$buysell->id) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('buysell.edit',$buysell->id) }}" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit
                                                </a>
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure want to delete this?');"><i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endsection

    @push('footer_scripts')
    <script>
        var sellValidation = $("#sellModelFrm").validate({
            rules: {
                close_val: {
                    required: true,
                    number: true
                },
            },
            messages: {
                close_val: {
                    required: "Please enter close value.",
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

        var closeValidation = $("#closeModelFrm").validate({
            rules: {
                close_val: {
                    required: true,
                    number: true
                },
            },
            messages: {
                close_val: {
                    required: "Please enter close value.",
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

        $(document).ready(function() {
            $('#buysell').DataTable({
                responsive: true,
                "dom": 'l<"toolbar">frtip',
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
            });

            $("div.toolbar").html('<a href="{{route("buysell.index")}}" style="float:right;"  class="btn btn-secondary btn-xs mx-2" data-toggle="tooltip" data-original-title="Back"><i class="fa fa-arrow-left"></i> Back</a>');

            $('.sellbtn').on('click', function(ev) {
                $('#buy_sell_id').val($(this).attr('data-buy-sell-id'));
                $('#sellModal').modal('toggle');
            });

            $('.closeSellmodel').on('click', function(ev) {
                $('#buy_sell_id').val('');
                $('#sellModelFrm')[0].reset()
                sellValidation.resetForm();
                $('#sellModal').modal('hide');
            });

            $('.closebtn').on('click', function(ev) {
                $('#buy_sell_id').val($(this).attr('data-buy-sell-id'));
                $('#closeModal').modal('toggle');
            });

            $('.closemodel').on('click', function(ev) {
                $('#buy_sell_id').val('');
                $('#closeModelFrm')[0].reset()
                closeValidation.resetForm();
                $('#closeModal').modal('hide');
            });

        });
    </script>
    @endpush
</x-app-layout>