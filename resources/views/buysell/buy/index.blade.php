<x-app-layout>
    @section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Buy and Sell</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Buy and Sell</h6>
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
                        <div style="width: 100%">
                            <div class="table-responsive">
                                <table id="buysell" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">#</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Exchange</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Total Qty</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Avg</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Buy Total Val</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Close Val</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Diff.</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">P&L</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">P&L %</th>
                                            <th class="text-uppercase text-primary text-xs font-weight-bolder opacity-7">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $i = 1;
                                    $profit_loss = 0;
                                    $total_value = 0;
                                    $total_value_all = 0;
                                    $total_qty = 0;
                                    ?>
                                    @if (count($buy_sell_result))
                                    <tbody>
                                        @foreach ($buy_sell_result as $buy_sell)
                                        <?php
                                        $total_value_all += str_replace(',', '', $buy_sell->total_value);
                                        $total_qty += str_replace(',', '', $buy_sell->total_quantity);
                                        if ($buy_sell->profit_loss) {
                                            $profit_loss += str_replace(',', '', $buy_sell->profit_loss);
                                            $total_value += str_replace(',', '', $buy_sell->total_value);
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-sm">{{ $i++ }}</td>
                                            <td class="text-sm">{{ $buy_sell->getBuySell->exchange }}</td>
                                            <td class="text-sm">@php echo strtoupper(date('d-M-Y', strtotime($buy_sell->getBuySell->date))); @endphp</td>
                                            <td class="text-sm">{{ number_format($buy_sell->total_quantity, 2) }}</td>
                                            <td class="text-sm">{{ number_format($buy_sell->total_average, 2) }}</td>
                                            <td class="text-sm">{{ number_format($buy_sell->total_value, 2) }}</td>
                                            <td class="text-sm">@if($buy_sell->close || $buy_sell->close == 0){{ number_format($buy_sell->close, 2) }}@else-@endif</td>
                                            <td class="text-sm">@if($buy_sell->difference || $buy_sell->difference == 0){{ number_format($buy_sell->difference, 2) }}@else-@endif</td>
                                            <td class="text-sm">@if($buy_sell->profit_loss || $buy_sell->profit_loss == 0){{ number_format($buy_sell->profit_loss, 2) }}@else-@endif</td>
                                            <td class="text-sm">@if($buy_sell->profit_loss_per || $buy_sell->profit_loss_per == 0){{ number_format($buy_sell->profit_loss_per, 2) }}@else-@endif</td>
                                            <td class="text-sm">
                                                <button type="button" data-buy-sell-id="{{$buy_sell->buy_sell_id}}" class="closebtn btn btn-danger btn-xs" data-original-title="close"><i class="fa fa-close"></i> Close</button>
                                                <a href="{{route('buysell.show',$buy_sell->buy_sell_id)}}" class="btn btn-info btn-xs" data-original-title="Info"><i class="fa fa-info-circle"></i> Info</a>
                                                <br>
                                                <button type="button" data-buy-sell-id="{{$buy_sell->buy_sell_id}}" class="sellbtn btn btn-success btn-xs" data-original-title="Sell"> <i class="fa fa-share"></i> Sell</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th>{{ number_format($total_qty, 2) }}</th>
                                            <th></th>
                                            <th>{{ number_format($total_value_all, 2) }}</th>
                                            <th colspan="2"></th>
                                            <th>{{ number_format($profit_loss, 2) }}</th>
                                            <?php $profit_loss_per = ($total_value == 0) ? 0 : (($profit_loss / $total_value) * 100); ?>
                                            <th colspan="2">{{ number_format($profit_loss_per,2) }}</th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Close Modal -->
    <div id="closeModal" class="modal fade" role="dialog" aria-labelledby="closeModalLabel">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('buysell.sellClose') }}" method="POST" id="closeModelFrm">
                    @csrf
                    <input type="hidden" value="" name="buy_sell_id" id="buy_sell_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Close</h4>
                        <button type="button" class="close closemodel btn" style="box-shadow: none;font-size: 25px;margin-bottom: 0;">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="close_val">Close Value</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="close_val" id="close_val" value="{{ old('close_val') }}" placeholder="Close Value">
                            </div>
                            @error('close_val') <p class="help-block"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="closebtn closemodel btn btn-default" data-original-title="Close">Close</button>
                        <button type="submit" class="btn btn-success" data-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Sell Modal -->
    <div id="sellModal" class="modal fade" role="dialog" aria-labelledby="sellModalLabel">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('buysell.sell') }}" method="POST" id="sellModelFrm">
                    @csrf
                    <input type="hidden" value="" name="buy_sell_id" id="buy_sell_id">
                    <div class="modal-header">
                        <h4 class="modal-title">Sell</h4>
                        <button type="button" class="close closeSellmodel btn" style="box-shadow: none;font-size: 25px;margin-bottom: 0;">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="close_val">Close Value</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="close_val" id="close_val" value="{{ old('close_val') }}" placeholder="Close Value">
                            </div>
                            @error('close_val') <p class="help-block"> {{$message}} </p>@enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="sellclosebtn closeSellmodel btn btn-default" data-original-title="Close">Close</button>
                        <button type="submit" class="btn btn-success" data-dismiss="modal">Sell</button>
                    </div>
                </form>
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

            $("div.toolbar").html('<a href="{{ route("buyExport")}}" style="float:right;"  class="btn btn-info btn-xs mx-2" data-toggle="tooltip" data-original-title="Export to Exce"><i class="fa fa-file-excel-o"></i> Export to Excel</a><a href="{{ route("buysell.create")}}" style="float:right;"  class="btn btn-primary btn-xs mx-2" data-toggle="tooltip" data-original-title="Add"><i class="fa fa-plus"></i> Add</a> ');

            $("#buysell").on("click", ".sellbtn", function() {
                $('#buy_sell_id').val($(this).attr('data-buy-sell-id'));
                $('#sellModal').modal('toggle');
            });

            $('.closeSellmodel').on('click', function() {
                $('#buy_sell_id').val('');
                $('#sellModelFrm')[0].reset()
                sellValidation.resetForm();
                $('#sellModal').modal('hide');
            });

            $("#buysell").on("click", ".closebtn", function() {
                $('#buy_sell_id').val($(this).attr('data-buy-sell-id'));
                $('#closeModal').modal('toggle');
            });

            $('.closemodel').on('click', function() {
                $('#buy_sell_id').val('');
                $('#closeModelFrm')[0].reset()
                closeValidation.resetForm();
                $('#closeModal').modal('hide');
            });

        });
    </script>
    @endpush
</x-app-layout>