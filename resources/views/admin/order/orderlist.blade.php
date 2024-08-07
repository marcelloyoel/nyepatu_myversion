@extends('partials.bodyPages')
@section('sidebar')
    @include('partials.sidebar')
@endsection
@section('container')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Order List</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        {{-- <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Order List</h6>
        </div> --}}
        <div class="card-body">
            <div class="table-responsive">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered display" id="myTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order's Id</th>
                                    <th>Ordered By</th>
                                    <th>Assigned To</th>
                                    <th>Max Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="sorting_1">{{ $order->id }}</td>
                                        <td>{{ $order->user->username }}</td>
                                        {{-- <td>{{ $order->laundrySepatu['laundrySepatuName'] }}</td> --}}
                                        <td>{{ $order->laundrySepatu->laundrySepatuName }}</td>
                                        <td>{{ $order->created_at->addWeek()->format('Y-m-d') }}</td>
                                        <td>
                                            @if ($order->status == 1)
                                                {{ 'Waiting' }}
                                            @elseif ($order->status == 2)
                                                {{ 'Accepted' }}
                                            @elseif ($order->status == 3)
                                                {{ 'Brushing Sole' }}
                                            @elseif ($order->status == 4)
                                                {{ 'Brushing Insole and Shoelace' }}
                                            @elseif ($order->status == 5)
                                                {{ 'Removing Stains' }}
                                            @elseif ($order->status == 6)
                                                {{ 'Quality Control' }}
                                            @elseif ($order->status == 7)
                                                {{ 'Awaiting Pickup' }}
                                            @elseif ($order->status == 8)
                                                {{ 'Finished' }}
                                            @elseif ($order->status == 9)
                                                {{ 'Rejected' }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="/order/{{ $order->id }}" class="btn btn-primary btn-circle mx-2">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
