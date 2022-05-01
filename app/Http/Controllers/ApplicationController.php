<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\application;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Response
     */
    public function index()
    {
        $concernedUser = Auth()->user();

        if ($concernedUser->hasRole('Manager')) {
            $applications = Application::paginate();
            return view('dashboard')->with(compact('applications'));
        } else if ($concernedUser->hasRole('Client')) {
            return view('dashboard');
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApplicationRequest $request
     * @return RedirectResponse|Response
     */
    public function store(StoreApplicationRequest $request)
    {
        $validated = $request->validated();
        $validated['fullname'] = Auth::user()->name;
        Application::create($validated);
        return redirect()->back()->with(['message' => 'Заявка отправлена успешно']);
    }

    /**
     * Display the specified resource.
     *
     * @param application $application
     * @return Response
     */
    public function show(application $application)
    {
        //
    }
}
