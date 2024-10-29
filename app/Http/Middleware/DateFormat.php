<?php

namespace App\Http\Middleware;

use App\Events\Common\DatesFormating;
use Closure;
use Illuminate\Support\Facades\Date;

class DateFormat
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->method() == 'POST') || ($request->method() == 'PATCH')) {
            $columns = new \stdClass();
            $columns->fields = [
                'paid_at',
                'due_at',
                'resumption_date',
                'start_date',
                'end_date',
                'expire_at',
            ];

            event(new DatesFormating($columns, $request));

            $fields = $columns->fields;

            foreach ($fields as $field) {
                $date = $request->get($field);

                if (empty($date)) {
                    continue;
                }

                if (Date::parse($date)->format('H:i:s') == '00:00:00') {
                    $new_date = Date::parse($date)->format('Y-m-d');
                } else {
                    $new_date = Date::parse($date)->toDateTimeString();
                }

                $request->request->set($field, $new_date);
            }
        }

        return $next($request);
    }
}
