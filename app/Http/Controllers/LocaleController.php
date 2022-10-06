<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{

    protected $previousRequest;
    protected $locale;

    public function switch_lang($locale)
    {

        $this->previousRequest = $this->getPreviousRequest();
        $segments = $this->previousRequest->segments();/*url*/
        try {
            if (array_key_exists($locale, config('locales.languages'))) {

                App::setLocale($locale);
                Lang::setLocale($locale);
                setlocale(LC_TIME, config('locales.languages')[$locale]['unicode']);
                Carbon::setLocale(config('locales.languages')[$locale]['lang']);
                Session::put('locale', $locale);

                if (config('locales.languages')[$locale]['rtl_support'] == 'rtl') {
                    Session::put('lang-rtl', true);
                } else {
                    Session::forget('lang-rtl');
                }

                /*if (isset($segments[1])) {
                    return $this->resolveModel(News::class, $segments[1], $locale);
                }*/

                return redirect()->back();

            }

            return redirect()->back();

        } catch (\Exception $exception) {
            return redirect()->back();
        }
    }

    protected function getPreviousRequest()
    {
        return request()->create(url()->previous());
    }

    /*protected function resolveModel($modelClass, $slug, $locale)
    {
        $model = $modelClass::where('slug->' . $locale, $slug)->first();

        if (is_null($model)) {

            foreach (config('locales.languages') as $key => $val) {
                $modelInLocale = $modelClass::where('slug->' . $key, $slug)->first();
                if ($modelInLocale) {
                    $newRoute = str_replace($slug, $modelInLocale->slug, urldecode(urlencode(route('news.show', $modelInLocale->slug))));
                    return redirect()->to($newRoute)->send();
                }
            }
            abort(404);
        }
        return $model;
    }*/

}
