<?php

namespace App\Http\Controllers\Admin;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class BusinessSettingsController extends Controller
{

    public function generalSetting()
    {
        return view('admin.business-setting.general-setting');
    }

    public function shippingSetting()
    {
        return view('admin.business-setting.shipping-setting');
    }

    public function smtpSetting()
    {
        return view('admin.business-setting.smtp-setting');
    }

    public function socialSetting()
    {
        return view('admin.business-setting.social-setting');
    }

    public function storeSettingEnv(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        Artisan::call('cache:clear');
        return back();
    }

    public function storeGeneralSetting(Request $request)
    {
        $data = [
            'site_name' => $request->site_name,
            'site_slogan' => $request->site_slogan,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keyword' => $request->seo_keyword,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'google_map' => $request->google_map,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'hotline' => $request->hotline,
            'email' => $request->email,
            'admin_email' => $request->admin_email,
            'cc_mail' => $request->cc_mail,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'zalo' => $request->zalo,
            'youtube' => $request->youtube,
            'messenger_facebook' => $request->messenger_facebook,
            'header' => $request->header,
            'footer' => $request->footer
        ];

        if ($request->logo) {
            $file = $request->file('logo');
            $filename = time() . '-' . $file->getClientOriginalName();
            $filename = str_replace(' ', '', $filename);
            $folder_upload = "/images/logo/";
            $file->move(base_path() . $folder_upload, $filename);
            $logo = $folder_upload . $filename;

            $data['logo'] = $logo;
        }

        if ($request->favicon) {
            $file = $request->file('favicon');
            $filename = time() . '-' . $file->getClientOriginalName();
            $filename = str_replace(' ', '', $filename);
            $folder_upload = "/images/favicon/";
            $file->move(base_path() . $folder_upload, $filename);
            $favicon = $folder_upload . $filename;

            $data['favicon'] = $favicon;
        }

        if ($request->seo_image) {
            $file = $request->file('seo_image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $filename = str_replace(' ', '', $filename);
            $folder_upload = "/images/seo/";
            $file->move(base_path() . $folder_upload, $filename);
            $seo_image = $folder_upload . $filename;

            $data['seo_image'] = $seo_image;
        }

        foreach ($data as $type => $value) {
            BusinessSetting::updateOrCreate(
                [
                    'type' => $type,
                ],
                [
                    'value' => $value
                ]
            );
        }

        Artisan::call('cache:clear');
        return back()->with('success_msg', 'Cập nhật cài đặt chung thành công.');
    }

    public function storeShippingSetting(Request $request)
    {
        $data = [
            'shipping_fee_for_hn_hcm' => $request->shipping_fee_for_hn_hcm,
            'shipping_fee' => $request->shipping_fee,
            'free_ship_for_total_bill' => $request->free_ship_for_total_bill,
            'free_ship_start_date' => $request->free_ship_start_date,
            'free_ship_end_date' => $request->free_ship_end_date,
            'free_ship_apply_for_bill' => $request->free_ship_apply_for_bill,
        ];

        foreach ($data as $type => $value) {
            BusinessSetting::updateOrCreate(
                [
                    'type' => $type,
                ],
                [
                    'value' => $value
                ]
            );
        }

        Artisan::call('cache:clear');
        return back()->with('success_msg', 'Cập nhật cài đặt vận chuyển thành công.');
    }

    /**
     * overWrite the Env File values.
     * @param String type
     * @param String value
     * @return \Illuminate\Http\Response
     */
    public function overWriteEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                file_put_contents(
                    $path,
                    str_replace(
                        $type . '="' . env($type) . '"',
                        $type . '=' . $val,
                        file_get_contents($path)
                    )
                );
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
            }
        }
    }
}
