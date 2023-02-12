<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
   <tbody>
      <tr>
         <td align="center" valign="top" style="padding:20px 0 20px 0">
            <table bgcolor="FFFFFF" cellspacing="0" cellpadding="10" border="0" width="750">
               <tbody>
                  <tr>
                     <td>
                        <div style="width: 100%; display: block;">
                           <a href="{{route('index')}}">
                              <img src="{{asset('images/welcome-email.jpg')}}" style="display: block; width: 100%;">
                           </a>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td valign="top" style="text-align: center; font-family: 'Times'; padding-top: 15px;">
                        <h2 style="font-size:22px; font-weight:normal; line-height:22px; margin:15px 0 15px 0; color: #000;">Welcome to Olaben! We're excited to share all things Olaben with you throughout the year.</h2>
                        <p style="font-size:16px; margin:0 0 8px 0">We hope you love and enjoy the brand as much as we do.</p>
                        <p style="font-size:16px; margin:0 0 8px 0">Can't wait to meet you & see you #olabenswear</p>
                        <div style="padding: 15px 25px; background: #f7f7f0; border-radius: 15px; max-width: 400px; display: block; margin: 15px auto;">
                           <p style="font-size: 15px; margin: 4px 0;">Just for you…</p>
                           <p style="font-size: 18px; text-transform: uppercase; margin: 0;">TAKE 10% OFF</p>
                           <p style="font-size: 18px; text-transform: uppercase; margin: 0;">YOUR FIRST ORDER</p>
                           <p style="padding-top: 10px; margin: 0; font-size: 16px; text-transform: uppercase;">
                              USE CODE: <strong>{{$details['code_discount']}}</strong>
                           </p>
                           <p style="font-size: 16px; margin: 0;">at checkout</p>
                           <a href="{{route('shop')}}" style="font-size: 16px; padding-top: 15px; display: block; text-align: center; color: #000; text-decoration: none;"><strong>SHOP NOW ></strong></a>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:10px 0 0 0"><a href="{{route('bestseller')}}" target="_blank"><img style="display:block;width:100%" src="{{asset('img/shop_the_best_ft.png')}}"></a></td>
                  </tr>
                  <tr>
                     <td style="padding: 0">
                        <div style="width: 750px; margin: 0 auto;display:block;text-align: center;">
                           <div style="width:calc(50% - 4px);width:-webkit-calc(50% - 4px); display: inline;float: left; font-family:Roboto; font-weight:normal;border:2px solid #ffffff; font-size:14px !important;">
                              <a href="{{route('newitems')}}" style="background-color:#000000;color:#ffffff;text-decoration:none;display:block;padding:28px 0;text-transform:uppercase;font-weight:bold;font-size:14px !important;width:100%;letter-spacing:2px">New Arrivals</a>
                           </div>
                           <div style="width:calc(50% - 4px);width:-webkit-calc(50% - 4px); display: inline;float: left; font-family:Roboto; font-weight:normal;border:2px solid #ffffff; font-size:14px !important;">
                              <a href="{{route('bestseller')}}" style="background-color:#000000;color:#ffffff;text-decoration:none;display:block;padding:28px 0;text-transform:uppercase;font-weight:bold;font-size:14px !important;width:100%;letter-spacing:2px">Best Sellers</a>
                           </div>
                           <div style="width:calc(50% - 4px);width:-webkit-calc(50% - 4px); display: inline;float: left; font-family:Roboto; font-weight:normal;border:2px solid #ffffff; font-size:14px !important;">
                              <a href="{{route('category.list', array('legging'))}}" style="background-color:#000000;color:#ffffff;text-decoration:none;display:block;padding:28px 0;text-transform:uppercase;font-weight:bold;font-size:14px !important;width:100%;letter-spacing:2px">Leggings</a>
                           </div>
                           <div style="width:calc(50% - 4px);width:-webkit-calc(50% - 4px); display: inline;float: left; font-family:Roboto; font-weight:normal;border:2px solid #ffffff; font-size:14px !important;">
                              <a href="{{route('category.list', array('mat'))}}" style="background-color:#000000;color:#ffffff;text-decoration:none;display:block;padding:28px 0;text-transform:uppercase;font-weight:bold;font-size:14px !important;width:100%;letter-spacing:2px">ACCESSORIES</a>
                           </div>
                        </div>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>