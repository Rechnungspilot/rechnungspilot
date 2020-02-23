<table height="81" width="100%" autosize="1" style="height: 81px; margin: 90px 0;">
    <tr>
        <td width="50%">
            @if($template->logo)
                <img src="{{ $template->url }}" height="50">
            @endif
        </td>
        <td width="50%" style="text-align: right;">
            <table>
                @if ($company->phonenumber)
                    <tr>
                        <td class="text-right" style="font-size: 10px;"><b>Telefon</b></td>
                        <td style="font-size: 10px;">{{ $company->phonenumber }}</td>
                    </tr>
                @endif
                @if ($company->email)
                    <tr>
                        <td class="text-right" style="font-size: 10px;"><b>E-Mail</b></td>
                        <td style="font-size: 10px;">{{ $company->email }}</td>
                    </tr>
                @endif
                @if ($company->web)
                    <tr>
                        <td class="text-right" style="font-size: 10px;"><b>Web</b></td>
                        <td style="font-size: 10px;">{{ $company->web }}</td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
</table>