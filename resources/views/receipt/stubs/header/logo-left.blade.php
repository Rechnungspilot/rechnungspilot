<table width="100%" autosize="1" style="margin-top: 70px; margin-bottom: 70px;">
    <tbody>
        <tr>
            <td style="width: 70%; vertical-align: top;" autosize="1">
                @if($template->logo)
                    <img src="{{ $template->url }}" height="120">
                @endif
            </td>
            <td style="width: 30%;">
                <table width="100%" autosize="1">
                    @if ($company->name)
                        <thead>
                            <tr>
                                <td class="text-right" colspan="2" width="80%" style=""><b>{{ $company->name }}</b></td>
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @if ($company->phonenumber)
                            <tr>
                                <td width="30%" class="text-right" style=""><b>Telefon</b></td>
                                <td width="70%" style="text-align: right;">{{ $company->phonenumber }}</td>
                            </tr>
                        @endif
                        @if ($company->email)
                            <tr>
                                <td width="30%" class="text-right" style=""><b>E-Mail</b></td>
                                <td width="70%" style="text-align: right;">{{ $company->email }}</td>
                            </tr>
                        @endif
                        @if ($company->web)
                            <tr>
                                <td width="30%" class="text-right" style=""><b>Web</b></td>
                                <td width="70%" style="text-align: right;">{{ $company->web }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>