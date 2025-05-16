<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $notifications = NotificationHelper::getUnreadNotification();
        // dd( $user->unreadNotifications, get_current_user());
        return view('notifications.index', compact('notifications'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {

        user('admin')->unreadNotifications->markAsRead();

//        foreach ($request->only('id') as $id)
//        {
//            user('admin')->notifications()->where('id', $id)->markAsRead();
//        }

        return redirect()->back()->with('message', 'Notification(s) marked as read');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        user('admin')->notifications()->delete();

//        foreach ($request->only('id') as $id)
//        {
//            user('admin')->notifications()->where('id', $id)->markAsRead();
//        }

        return redirect()->back()->with('message', 'Notification(s) Successfully Deleted');
    }
}
