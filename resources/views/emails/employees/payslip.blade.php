@extends('layout.email')
@section('content')
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Dear {{$pay_summary->employee->first_name}},</p>
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Please find attached your payslip for {{$pay_summary->payPeriod->pay_month}}.
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Should your payslip appears to be incorrect, please bring this to our attention as soon as possible for investigation and rectification.
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">In order to protect the confidentiality of your financial information, your payslip has been attached to this email in "Secure PDF" format.
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">You are required to enter a “unique password” to open your payslip. Kindly follow the procedure detailed below.
    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">To view the document enter your date of birth in the format (DDMMYYYY) in the password field of the PDF document. (Example: 12th June 1995, password would be 12061995)

    <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Cheers!<br/>Payroll Team</p>
@endsection
