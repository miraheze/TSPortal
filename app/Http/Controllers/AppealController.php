<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Controller class for all Appeal actions
 */
class AppealController extends Controller
{
    /**
     * Indexes all appeals, with filters for non-privileged users
     *
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $allAppeals = Appeal::all();

        $query = $request->query();

        foreach ($query as $type => $key) {
            if (! $key) {
                continue;
            }
            if ($type === 'assigned') {
                $allAppeals = $allAppeals->where($type, User::findById((int) $key));
            } elseif (in_array($type, ['type', 'outcome'])) {
                if ($key === 'unknown') {
                    $key = null;
                }

                $allAppeals = $allAppeals->where($type, $key);
            }
        }

        if ($request->input('closed')) {
            $allAppeals = $allAppeals->whereNotNull('reviewed');
        } else {
            $allAppeals = $allAppeals->whereNull('reviewed');
        }

        return view('appeals')
            ->with('appeals', $allAppeals);
    }

    /**
     * Shows a specific appeal
     *
     *
     * @return Application|Factory|View
     */
    public function show(Appeal $appeal)
    {
        return view('appeal.view')->with('appeal', $appeal);
    }

    /**
     * Processor for processing updates to an appeal
     *
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Appeal $appeal, Request $request)
    {
        $allInputs = $request->input();
        unset($allInputs['_token'], $allInputs['_method']);
        $appeal->update(
            [
                'review' => json_encode($allInputs),
                'assigned' => auth()->id(),
                'outcome' => $allInputs['appeal-outcome'],
                'reviewed' => now(),
            ]
        );

        request()->session()->flash('successFlash', __('appeal').' '.__('toast-updated'));

        return redirect("/appeal/{$appeal->id}");
    }
}
