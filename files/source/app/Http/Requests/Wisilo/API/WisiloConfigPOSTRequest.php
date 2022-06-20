<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloConfig;

class WisiloConfigPOSTRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|regex:/^[\p{L}0-9 .]+$/i',
            'type' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $currentObject = WisiloConfig::where('id', $this->id)->first();
            



            
            /*if (0 == $this->deleted)
            {
                 if ((0 != strtotime($this->ock_tarihi)) && ($this->ock_tarihi <= $this->talep_tarihi)) {
                    $validator->errors()->add(
                        'ock_tarihi',
                        __('OÇK Tarihi ') . date('d.m.Y', strtotime($this->talep_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->pdk_tarihi)) && ($this->pdk_tarihi <= $this->ock_tarihi)) {
                    $validator->errors()->add(
                        'pdk_tarihi',
                        __('PDK Tarihi ') . date('d.m.Y', strtotime($this->ock_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->son_teklif_alma_tarihi)) && ($this->son_teklif_alma_tarihi <= $this->pdk_tarihi)) {
                    $validator->errors()->add(
                        'son_teklif_alma_tarihi',
                        __('Son Teklif Alma Tarihi ') . date('d.m.Y', strtotime($this->pdk_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->teklif_degerlendirme_tarihi)) && ($this->teklif_degerlendirme_tarihi <= $this->son_teklif_alma_tarihi)) {
                    $validator->errors()->add(
                        'teklif_degerlendirme_tarihi',
                        __('Teklif Değerlendirme Tarihi ') . date('d.m.Y', strtotime($this->son_teklif_alma_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->yuklenici_secim_tarihi)) && ($this->yuklenici_secim_tarihi <= $this->teklif_degerlendirme_tarihi)) {
                    $validator->errors()->add(
                        'yuklenici_secim_tarihi',
                        __('Yüklenici Seçim Tarihi ') . date('d.m.Y', strtotime($this->teklif_degerlendirme_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->sozlesme_imza_tarihi)) && ($this->sozlesme_imza_tarihi <= $this->yuklenici_secim_tarihi)) {
                    $validator->errors()->add(
                        'sozlesme_imza_tarihi',
                        __('Sözleşme İmza Tarihi ') . date('d.m.Y', strtotime($this->yuklenici_secim_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }
                
                if ((0 != strtotime($this->planlanan_t0_tarihi)) && ($this->planlanan_t0_tarihi <= $this->sozlesme_imza_tarihi)) {
                    $validator->errors()->add(
                        'planlanan_t0_tarihi',
                        __('Planlanan T0 Tarihi ') . date('d.m.Y', strtotime($this->sozlesme_imza_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->planlanan_teslim_tarihi)) && ($this->planlanan_teslim_tarihi <= $this->planlanan_t0_tarihi)) {
                    $validator->errors()->add(
                        'planlanan_teslim_tarihi',
                        __('Planlanan Teslim Tarihi ') . date('d.m.Y', strtotime($this->planlanan_t0_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }

                if ((0 != strtotime($this->t0_tarihi)) && ($this->t0_tarihi <= $this->sozlesme_imza_tarihi)) {
                    $validator->errors()->add(
                        't0_tarihi',
                        __('T0 Tarihi ') . date('d.m.Y', strtotime($this->sozlesme_imza_tarihi)) . ' tarihinden büyük olmalıdır.'
                    );
                }
            } // if (null == $this) */

            $this->customValidator($currentObject, $validator);

            sleep(2);
        });
        return;
    }

    public function customValidator($currentObject, $validator) {
        $objectWisilo = new Wisilo();
        $parent_key = $this->parent;
        $key = $objectWisilo->convertTitleToConfigName($this->title);

        if ('' != $parent_key) {
            $key = $parent_key . '.' . $key;
        }

        $anyObject = WisiloConfig::where('id', '!=', $this->id)->where('deleted', 0)->where('__key', '==', $key)->first();

        if (null !== $anyObject) {
            $validator->errors()->add(
                '__key',
                __('This element title is in use. Please try different title.'));
        } // if (null !== $anyObject) {
    }

}