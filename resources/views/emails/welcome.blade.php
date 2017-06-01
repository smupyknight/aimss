<!DOCTYPE html>
<html>
<body style="margin:0; padding:0;">
<div text="#000000" style="background-color:#f6f7f8;width:100%!important">
    <div>
        <table style="font-family:Helvetica,Arial,sans-serif" border="0" cellspacing="0" cellpadding="0" width="100%" align="center" bgcolor="#f6f7f8">
            <tbody>
                <tr>
                    <td>
                        <table style="font-size:11px;line-height:14px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="600" align="center">
                            <tbody>
                                <tr>
                                    <td>
                                        <p style="margin: 30px 0px 5px;">
                                            <a href="#" target="_blank"><img src="{{url('images/aimss-logo-web-small.png')}}" alt="Aimss" style="display:block; width:100%; height:auto; max-width:270px; margin:0 auto;"></a>
                                        </p>
                                        <h3 style="font-size: 22px; font-weight: 700; margin: 10px 0px 0px; color:#002a60; text-align:center; line-height:1;">INCIDENT MANAGEMENT APP</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table><br/>
                        <table style="font-size:13px;line-height:18px;color:#666666;border-radius:0px;border:#ccc 1px solid" border="0" cellspacing="0" cellpadding="0" width="600" align="center" bgcolor="#ffffff">
                            <tbody>
                                <tr>
                                    <td>
                                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="648" align="center">
                                            <tbody>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td>
                                                        <h1 style="font-size: 32px; font-weight: 700; margin: 5px 0px; color:#002a60; text-align:center;">WELCOME!</h1>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td>
                                                        <h2 style="font-size: 22px; font-weight: 500; margin: 10px 0px;">Hi {{ $user->first_name." ".$user->last_name }},</h2>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td style="font-size:14px;">
                                                        <p style="margin-bottom:20px;">You have been invited to use the AIMSS Incident Reporting app.</p>
                                                        <p style="margin-bottom:20px;">Please click this link to confirm your registration.</p>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="35">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td style="line-height:1; text-align:center;">
                                                        <a href="{{ request()->root() }}" style="border:1px solid #f55a37; border-radius:10px; background-color:#f26a4b; color:#fff; font-size:18px; padding:12px 50px; text-decoration:none;">CONFIRM YOUR ACCOUNT</a>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="30">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td style="font-size:14px; line-height:20px;" align="center"><p><a href="{{ config('aimss')['appStore_link'] }}" style="color:#333;">haven't got the app yet? Download it here.</a></p></td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="20">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="600" align="center">
                            <tbody>
                                <tr>
                                    <td style="line-height:0;font-size:0" colspan="3" height="20">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="10">&nbsp;</td>
                                    <td style="text-align:center;">
                                        &copy; Australian Institude of Motor Sport Safety <br> You receiving this email because you have signed up with the Australian Institude of Motor Sport Safety App <br> <a style="color:#666;text-decoration:underline" href="#" target="_blank">Terms & Conditions</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="line-height:0;font-size:0" colspan="3" height="40">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>