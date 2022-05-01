<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Auth;
use Carbon\CarbonImmutable;
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
            $applications = Application::orderBy('read', 'asc')->paginate(3);

            foreach ($applications as $k => $v) {
                empty($applications[$k]->getMedia('public')->first()) ?: $applications[$k]->getMedia('public')->first()->getUrl();
            }
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
     * @return RedirectResponse
     */
    public function store(StoreApplicationRequest $request)
    {
        if (Auth::user()->applications()->first()) {
            $lastApplicationAt = Auth::user()->applications()->latest()->first()->created_at;

            $start = CarbonImmutable::make($lastApplicationAt);
            $end = $lastApplicationAt->addDay(1);
            if (\Carbon\Carbon::now()->between($start, $end)) {
                return redirect()->back()->withErrors(['Вы уже отправляли заявку сегодня. Следующая возможность появится '.$end->format('d.m.Y H:i')]);
            }
        }

        $validated = $request->validated();
        $validated['fullname'] = Auth::user()->name;
        $validated['user_id'] = Auth::id();
        $app = Application::create($validated);
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $app->addMediaFromRequest('file')->toMediaCollection();
        }
        SendEmailJob::dispatch($validated);
        //dispatch(new App\Jobs\SendEmailJob($details));
        return redirect()->back()->with(['message' => 'Заявка отправлена успешно']);
    }

    /**
     * Display the specified resource.
     *
     * @param Application $application
     * @return Response
     */
    public function show(Application $application)
    {
        //
    }

    public function read(int $id)
    {
        Application::whereId($id)->update(['read' => true]);
        return redirect()->back()->with(['message' => 'Статус успешно обновлен']);
    }
}
