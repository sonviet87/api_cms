
<div style="display: flex; justify-content: center; align-items: center; background: #edf2f7;text-align: center;" >
<table style="box-sizing: border-box;background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
    <tr>
        <td style="padding: 25px 0;">
            <table style="box-sizing: border-box;margin: 0; padding: 0; width: 100%;text-align: center">
                <tr>
                    <td>
                        <img src="{{env("APP_URL_DOMAIN")."/images/logo.png"}}" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 25px 0;">
            <table style="box-sizing: border-box;margin: 0; padding: 20px; width: 100%;text-align: center;width: 570px;background: #fff;border-radius: 2px; border-width: 1px;margin: 0 auto;">
                <tr>
                    <td>
                        <h3>{{$name}}</h3>
                        <p>Tình Trang: <b>{{$status}}</b></p>
                        <br/>
                        <a href="{{env("APP_URL_FE").'admin/fps/'.$id}}" target="_blank" style="padding: 10px 30px;background: #2d3748;color:#fff;border-radius: 5px;" >Truy cập</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 25px 0;">
            © 2022 MVTTech. All rights reserved.
        </td>
    </tr>
</table>
</div>






