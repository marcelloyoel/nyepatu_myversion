<?php

namespace App\Http\Controllers;

use App\Models\LaundrySepatu;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    protected $model;
    public function __construct()
    {
        $this->model = new LaundrySepatu();
    }
    public function index(Request $request)
    {
        // dd('hello');
        if (Auth::user()->group_id == 1) {
            $mapsController = new MapsController();
            $mapsController->calculateDistance($request);
            $calculatedDistances = Session::get('calculated_distances');

            // Get all laundries
            // $laundriesQuery = LaundrySepatu::query();
            $laundriesQuery = LaundrySepatu::all();

            // off dulu soalnya servicenya abis
            // Sort the laundries by distance
            // $laundries = $laundriesQuery->get()->sortBy(function ($laundry) use ($calculatedDistances) {
            //     return (float) str_replace(',', '', $calculatedDistances[$laundry->user_id] ?? PHP_INT_MAX);
            // });

            // off dulu soalnya servicenya abis
            // Filter laundries with distances less than or equal to 25 km
            // $sortedLaundries = $laundries->filter(function ($laundry) use ($calculatedDistances) {
            //     $distance = (float) str_replace(',', '', $calculatedDistances[$laundry->user_id] ?? PHP_INT_MAX);
            //     return $distance <= 25;
            // });

            // Paginate the sorted laundries with 8 items per page
            // $perPage = 8;
            // $currentPage = $request->query('page') ?? 1;
            // $offset = ($currentPage - 1) * $perPage;
            // $currentPageItems = $sortedLaundries->slice($offset, $perPage)->values();
            // $total = $laundries->count();

            // $paginatedLaundries = new \Illuminate\Pagination\LengthAwarePaginator(
            //     $currentPageItems,
            //     $total,
            //     $perPage,
            //     $currentPage,
            //     ['path' => $request->url()]
            // );

            return view('home', [
                'title' => 'Halaman Home',
                'calculatedDistances' => $calculatedDistances,
                'laundries' => $laundriesQuery,
            ]);
        } else if (Auth::user()->group_id == 2) {
            $user = Auth::user();
            $laundry = $user->laundrySepatu;
            $doneOrder = $this->model->getDoneOrder($laundry->id);
            $activeOrder = $this->model->getActiveOrder($laundry->id);
            $cancelledOrder = $this->model->getCancelledOrder($laundry->id);
            $total = $this->model->getTotalUang($laundry->id);
            // dd($total);
            $services = Service::where('laundry_sepatu_id', $laundry->id)->get();
            return view('homeLaundry', [
                'title'         => 'Halaman Home',
                'laundry'       => $laundry,
                'services'      => $services,
                'doneOrder'     => $doneOrder,
                'activeOrder'   => $activeOrder,
                'cancelledOrder' => $cancelledOrder,
                'total'         => $total
            ]);
        } else {
            return view('homeAdmin', [
                'title' => 'Halaman Home',
                'users' => User::all(),
                'javascript'   => 'profile.js'
            ]);
        }
    }
}
// $laundry = LaundrySepatu::find($id);
// return view('buyer.laundry', [
//     'title' => $laundry->laundrySepatuName,
//     'laundry'   => $laundry
// ]);
