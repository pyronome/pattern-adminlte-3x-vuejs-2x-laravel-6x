<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\Http\Requests\AdminLTE\API\BrandingPOSTRequest;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    
    public function get(Request $request)
    {
        $objectAdminLTE = new AdminLTE();
        $brand_data = $objectAdminLTE->getBrandData();
        return $brand_data;
    }
    
    public function post(BrandingPOSTRequest $request)
    {
        $objectAdminLTE = new AdminLTE();

        $brand_name = $request->input('name');
        $logo = $request->input('logo');
        $file_name = $request->input('file_name');
        $old_logo_path = '';
        $new_logo_path = '';

        if (Storage::disk('local')->exists('config/brand_json.php')) {
            $brandJSON = Storage::disk('local')->get('config/brand_json.php');
            $brand_data = json_decode($brandJSON, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $old_logo_path = $brand_data['logo'];
            
            if ('logo_not_changed' != $file_name) {
                $temp = explode('.', $file_name);
                $file_name = $objectAdminLTE->convertNameToFileName($temp[0]) . '.' . $temp[1];
                $new_logo_path = 'adminltebrand/logo/' . $file_name;
    
                if ($old_logo_path != $new_logo_path) {
                    $content = $objectAdminLTE->getContentFromURI($logo);
                    Storage::disk('local')->put('public/' . $new_logo_path, $content);

                    Storage::disk('local')->delete('public/' .$old_logo_path);
                } 
            } else {
                $new_logo_path = $old_logo_path;
            }

        } else {
            $brandJSON = config('brand_json');
            $brand_data = json_decode($brandJSON, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            
            if ('logo_not_changed' != $file_name) {
                $temp = explode('.', $file_name);
                $file_name = $objectAdminLTE->convertNameToFileName($temp[0]) . '.' . $temp[1];
                $new_logo_path = 'adminltebrand/logo/' . $file_name;
                $content = $objectAdminLTE->getContentFromURI($logo);
                           
                Storage::disk('local')->put('public/' . $new_logo_path, $content);
            } else {
                $content = file_get_contents(base_path('public/img/adminlte/AdminLTELogo.png'));
                Storage::disk('local')->put('public/adminltebrand/logo/AdminLTELogo.png', $content);
                $new_logo_path = 'adminltebrand/logo/AdminLTELogo.png';
            }
        } 

        $brandJSON = '{"name": "' . $brand_name . '", "logo": "' . $new_logo_path . '"}';
        
        Storage::disk('local')->put('config/brand.json', $brandJSON);
        
        return ['message' => "Success"];
    }
    
}
