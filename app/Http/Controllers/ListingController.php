<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::all();
        return view('Listings', compact('listings'));
    }

    public function show($id)
    {
        $listing = Listing::find($id);
        return view('Listing', compact('listing'));
    }
}
