<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\TourRepositoryInterface;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        protected readonly TourRepositoryInterface $tourRepository,
        protected readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function index()
    {
        $featuredTours = $this->tourRepository->featured(6);
        $cities = $this->tourRepository->cities();

        return Inertia::render('Home', compact('featuredTours', 'cities'));
    }
}
