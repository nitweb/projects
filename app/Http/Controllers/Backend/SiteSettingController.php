<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function SiteSetting()
    {
        $siteSetting = SiteSetting::find(1);
        return view('admin.site_setting.site_setting_all', compact('siteSetting'));
    }

    public function SiteSettingUpdate(Request $request)
    {

        $id = $request->id;
        $siteSetting =  SiteSetting::findOrFail($id);
        $siteSetting->title = $request->title;
        $siteSetting->copyright = $request->copyright;

        if ($request->file('logo')) {
            $file = $request->file('logo');
            if ($siteSetting->logo != null) {
                @unlink(public_path($siteSetting->logo));
            }
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(('upload/site_images'), $fileName);
            $siteSetting->logo = 'upload/site_images/' . $fileName;
            $siteSetting->update();
        } elseif ($request->file('favicon')) {
            $file = $request->file('favicon');
            if ($siteSetting->logo != null) {
                @unlink(public_path($siteSetting->favicon));
            }
            $fileName = date('YmdHi') . $file->getClientOriginalName();
            $file->move(('upload/site_images'), $fileName);
            $siteSetting->favicon = 'upload/site_images/' . $fileName;
            $siteSetting->update();
        } elseif ($request->file('logo') && $request->file('favicon')) {
            $logoFile = $request->file('logo');
            $logoFileName = date('YmdHi') . $logoFile->getClientOriginalName();
            $logoFile->move(('upload/site_images'), $logoFileName);

            // favicon
            $favFile = $request->file('favicon');
            $favFileName = date('YmdHi') . $favFile->getClientOriginalName();
            $favFile->move(('upload/site_images'), $favFileName);

            // unlink old images
            if (($siteSetting->logo && $siteSetting->favicon) != null) {
                @unlink(public_path($siteSetting->logo));
                @unlink(public_path($siteSetting->favicon));
            }

            // save to db
            $siteSetting->logo = 'upload/site_images/' . $logoFileName;
            $siteSetting->favicon = 'upload/site_images/' . $favFileName;
            $siteSetting->update();
        } else {
            $siteSetting->title = $request->title;
            $siteSetting->copyright = $request->copyright;
            $siteSetting->update();
        }


        $notification = array(
            'message' => 'Site Setting Updated Successfully!',
            'alert_type' => 'success'
        );
        return redirect()->route('site.setting')->with($notification);


        // if ($request->file('logo') && $request->file('favicon')) {
        //     $logo = $request->file('logo');
        //     $favicon = $request->file('favicon');
        //     if (($siteSetting->logo && $siteSetting->favicon) != null) {
        //         @unlink(public_path($siteSetting->logo));
        //         @unlink(public_path($siteSetting->favicon));
        //     }
        //     $logo_gen = hexdec(uniqid()) . '.' . $logo->getClientOriginalExtension();
        //     $favicon_gen = hexdec(uniqid()) . '.' . $favicon->getClientOriginalExtension();

        //     Image::make($logo)->resize(137, 30)->save('upload/site_images/' . $logo_gen);
        //     Image::make($favicon)->resize(64, 64)->save('upload/site_images/' . $favicon_gen);
        //     $logo_url = 'upload/site_images/' . $logo_gen;
        //     $fav_url = 'upload/site_images/' . $favicon_gen;


        //     $siteSetting->title = $request->title;
        //     $siteSetting->copyright = $request->copyright;
        //     $siteSetting->logo = $logo_url;
        //     $siteSetting->favicon = $fav_url;
        //     $siteSetting->update();

        //     $notification = array(
        //         'message' => 'Site Setting Updated Successfully!',
        //         'alert_type' => 'success'
        //     );
        //     return redirect()->route('site.setting')->with($notification);
        // } elseif ($request->file('logo')) {
        //     $logo = $request->file('logo');
        //     if ($siteSetting->logo  != null) {
        //         @unlink(public_path($siteSetting->logo));
        //     }
        //     $logo_gen = hexdec(uniqid()) . '.' . $logo->getClientOriginalExtension();

        //     Image::make($logo)->resize(137, 30)->save('upload/site_images/' . $logo_gen);
        //     $logo_url = 'upload/site_images/' . $logo_gen;


        //     $siteSetting->title = $request->title;
        //     $siteSetting->copyright = $request->copyright;
        //     $siteSetting->logo = $logo_url;
        //     $siteSetting->update();

        //     $notification = array(
        //         'message' => 'Site Setting Updated Successfully!',
        //         'alert_type' => 'success'
        //     );
        //     return redirect()->route('site.setting')->with($notification);
        // } elseif ($request->file('favicon')) {
        //     $favicon = $request->file('favicon');
        //     if ($siteSetting->favicon  != null) {
        //         @unlink(public_path($siteSetting->favicon));
        //     }
        //     $favicon_gen = hexdec(uniqid()) . '.' . $favicon->getClientOriginalExtension();

        //     Image::make($favicon)->resize(64, 64)->save('upload/site_images/' . $favicon_gen);
        //     $fav_url = 'upload/site_images/' . $favicon_gen;

        //     $siteSetting->title = $request->title;
        //     $siteSetting->copyright = $request->copyright;
        //     $siteSetting->favicon = $fav_url;
        //     $siteSetting->update();

        //     $notification = array(
        //         'message' => 'Site Setting Updated Successfully!',
        //         'alert_type' => 'success'
        //     );
        //     return redirect()->route('site.setting')->with($notification);
        // } else {
        //     $siteSetting->title = $request->title;
        //     $siteSetting->copyright = $request->copyright;
        //     $siteSetting->update();

        //     $notification = array(
        //         'message' => 'Site Setting Updated Successfully!',
        //         'alert_type' => 'success'
        //     );
        //     return redirect()->route('site.setting')->with($notification);
        // }
    }
}
