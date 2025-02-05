<table style="width:100%;" class="company-info-header">
    <thead>
    <tr>
        <td style="width: 25%" colspan="3">
            @if(!isset($data['report_type']) || (isset($data['report_type']) && $data['report_type'] != 'excel'))
                <img src="{{ $data["logo"] }}" style="margin-bottom:10px!important;" height="80" alt="" />
            @endif
        </td>
        <td style="text-align: center" colspan="6"><h3 style="text-align: center; padding: 15px;">{{isset($data['report_title'])?$data['report_title']:""}}</h3></td>
{{--        <td style="text-align:right;">--}}
{{--            @if(!isset($data['report_type']) || (isset($data['report_type']) && $data['report_type'] != 'excel'))--}}
{{--                <pre class="header-pre">--}}
{{--                    {{ settings("company_address") }}--}}
{{--                </pre>--}}
{{--            @endif--}}
{{--        </td>--}}
        <td style="width: 25%" colspan="3">
    </tr>
    </thead>
</table>
