@extends('layout.email')
@section('content')
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Dear {{$employee->first_name}},</p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">On behalf of the entire team here at
        {{$employee->company->company_name}}, we would like to extend our warmest wishes to you on your special day. Today is a day to celebrate you and all that you bring to our company.</p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">We are truly grateful for your hard work, dedication, and positive attitude. Your contributions have been invaluable and we are lucky to have you as part of our team.</p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Enjoy your day filled with joy, laughter, and the love of your loved ones. May the coming year bring you continued growth, both personally and professionally. </p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><b>Happy Birthday! </b></p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Cheers!<br/>HR Department</p>
@endsection
