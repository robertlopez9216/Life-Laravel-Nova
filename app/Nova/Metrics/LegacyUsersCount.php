<?php

namespace App\Nova\Metrics;

use App\User;
use Illuminate\Http\Request;
use App\Enums\SubscriptionType;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class LegacyUsersCount extends Value
{
    public $name = 'Legacy User Count';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->result(User::whereSubscriptionType(SubscriptionType::TheEssentialLifeMembershipLegacy)->count())
            ->format('0,0');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the appropriate cache key for the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return string
     */
    protected function getCacheKey(NovaRequest $request)
    {
        return parent::getCacheKey($request) . session()->get('selected_language');
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'legacy-users-count';
    }
}
