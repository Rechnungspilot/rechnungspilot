<table height="81" width="100%" autosize="1" style="height: 81px; margin: 90px 0;">
    <tr>
        <td width="50%"><h2 style="font-size: 24;">{{ $company->name }}</h2></td>
        <td width="50%" style="text-align: right;">
            @if($template->logo)
                <img src="{{ $template->url }}" height="50">
            @endif
        </td>
    </tr>
</table>