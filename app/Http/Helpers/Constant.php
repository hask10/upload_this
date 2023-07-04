<?php

use App\Models\Localization;
use App\Models\MediaManager;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Config;

if (!function_exists('getTheme')) {
    # get system theme
    function getTheme()
    {
        if (session('theme') != null && session('theme') != '') {
            return session('theme');
        }
        return Config::get('app.theme');
    }
}

if (!function_exists('getView')) {
    # get view of theme
    function getView($path, $data = [])
    {
        return view('frontend.' . getTheme() . '.' . $path, $data);
    }
}

if (!function_exists('getViewRender')) {
    # get view of theme with render
    function getViewRender($path, $data = [])
    {
        return view('frontend.' . getTheme() . '.' . $path, $data)->render();
    }
}

if (!function_exists('cacheClear')) {
    # clear server cache
    function cacheClear()
    {
        try {
            Artisan::call('cache:forget spatie.permission.cache');
        } catch (\Throwable $th) {
            //throw $th;
        }

        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
    }
}

if (!function_exists('clearPaymentSession')) {
    # clear session cache
    function clearPaymentSession()
    {
        session()->forget('package_id');
        session()->forget('amount');
        session()->forget('payment_method');
    }
}

if (!function_exists('csrfToken')) {
    #  Get the CSRF token value. 
    function csrfToken()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }
        throw new RuntimeException('Session store not set.');
    }
}

if (!function_exists('paginationNumber')) {
    # return number of data per page
    function paginationNumber($value = null)
    {
        return $value != null ? $value : env('DEFAULT_PAGINATION');
    }
}

if (!function_exists('areActiveRoutes')) {
    # return active class
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
        return '';
    }
}

if (!function_exists('validatePhone')) {
    # validatePhone
    function validatePhone($phone)
    {
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);
        return $phone;
    }
}


if (!function_exists('staticAsset')) {
    # return path for static assets
    function staticAsset($path, $secure = null)
    {
        if (str_contains(url('/'), '.test') || str_contains(url('/'), 'http://127.0.0.1:')) {
            return app('url')->asset('' . $path, $secure) . '?v=' . env('APP_VERSION');
        }
        return app('url')->asset('public/' . $path, $secure) . '?v=' . env('APP_VERSION');
    }
}

if (!function_exists('uploadedAsset')) {
    #  Generate an asset path for the uploaded files. 
    function uploadedAsset($fileId)
    {
        $mediaFile = MediaManager::find($fileId);
        if (!is_null($mediaFile)) {
            if (str_contains(url('/'), '.test') || str_contains(url('/'), 'http://127.0.0.1:')) {
                return app('url')->asset('' . $mediaFile->media_file);
            }
            return app('url')->asset('public/' . $mediaFile->media_file);
        }
        return '';
    }
}


if (!function_exists('localize')) {
    # add / return localization 
    function localize($key, $lang = null, $localize = true)
    {
        if ($localize == false) {
            return $key;
        }

        if ($lang == null) {
            $lang = App::getLocale();
        }

        $t_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

        $localization_default = Cache::rememberForever('localizations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
            return Localization::where('lang_key', env('DEFAULT_LANGUAGE', 'en'))->pluck('t_value', 't_key');
        });

        if (!isset($localization_default[$t_key])) {
            # add new localization
            newLocalization(env('DEFAULT_LANGUAGE', 'en'), $t_key, $key);
        }

        # return user session lang
        $localization_user = Cache::rememberForever("localizations-{$lang}", function () use ($lang) {
            return Localization::where('lang_key', $lang)->pluck('t_value', 't_key')->toArray();
        });

        if (isset($localization_user[$t_key])) {
            return trim($localization_user[$t_key]);
        }

        return trim(__($t_key));
    }
}

if (!function_exists('newLocalization')) {
    # new localization
    function newLocalization($lang, $t_key, $key)
    {
        $localization = new Localization;
        $localization->lang_key = $lang;
        $localization->t_key = $t_key;
        $localization->t_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
        $localization->save();

        # clear cache
        Cache::forget('localizations-' . $lang);

        return trim($key);
    }
}

if (!function_exists('writeToEnvFile')) {
    # write To Env File
    function writeToEnvFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                file_put_contents($path, str_replace(
                    $type . '="' . env($type) . '"',
                    $type . '=' . $val,
                    file_get_contents($path)
                ));
            } else {
                file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
            }
        }
    }
}

if (!function_exists('getFileType')) {
    #  Get file Type
    function getFileType($type)
    {
        $fileTypeArray = [
            // audio
            "mp3"       =>  "audio",
            "wma"       =>  "audio",
            "aac"       =>  "audio",
            "wav"       =>  "audio",

            // video
            "mp4"       =>  "video",
            "mpg"       =>  "video",
            "mpeg"      =>  "video",
            "webm"      =>  "video",
            "ogg"       =>  "video",
            "avi"       =>  "video",
            "mov"       =>  "video",
            "flv"       =>  "video",
            "swf"       =>  "video",
            "mkv"       =>  "video",
            "wmv"       =>  "video",

            // image 
            "png"       =>  "image",
            "svg"       =>  "image",
            "gif"       =>  "image",
            "jpg"       =>  "image",
            "jpeg"      =>  "image",
            "webp"      =>  "image",

            // document 
            "doc"       =>  "document",
            "txt"       =>  "document",
            "docx"      =>  "document",
            "pdf"       =>  "document",
            "csv"       =>  "document",
            "xml"       =>  "document",
            "ods"       =>  "document",
            "xlr"       =>  "document",
            "xls"       =>  "document",
            "xlsx"      =>  "document",

            // archive  
            "zip"       =>  "archive",
            "rar"       =>  "archive",
            "7z"        =>  "archive"
        ];
        return isset($fileTypeArray[$type]) ? $fileTypeArray[$type] : null;
    }
}

if (!function_exists('fileDelete')) {
    # file delete 
    function fileDelete($file)
    {
        if (File::exists('public/' . $file)) {
            File::delete('public/' . $file);
        }
    }
}

if (!function_exists('getSetting')) {
    # return system settings value
    function getSetting($key, $default = null)
    {
        try {
            $settings = Cache::remember('settings', 86400, function () {
                return SystemSetting::all();
            });

            $setting = $settings->where('entity', $key)->first();

            return $setting == null ? $default : $setting->value;
        } catch (\Throwable $th) {
            return $default;
        }
    }
}

if (!function_exists('renderStarRating')) {
    # render ratings
    function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i data-feather='star' width='16' height='16' class='text-primary'></i>";

        $rating = $rating <= $maxRating ? $rating : $maxRating;
        $fullStarCount = (int)$rating;

        $html = str_repeat($fullStar, $fullStarCount);
        echo $html;
    }
}

if (!function_exists('renderStarRatingFront')) {
    # render ratings frontend
    function renderStarRatingFront($rating, $maxRating = 5)
    {
        $fullStar = '<li><i class="las la-star text-warning"></i></li>';

        $rating = $rating <= $maxRating ? $rating : $maxRating;
        $fullStarCount = (int)$rating;

        $html = str_repeat($fullStar, $fullStarCount);
        echo $html;
    }
}

if (!function_exists('formatWords')) {
    # format Words 
    function formatWords($words)
    {
        if ($words < 10000) {
            // less than 10 thousands
            $words = $words;
        } else if ($words < 1000000) {
            // less than a million
            $words = $words / 1000  . 'k';
        } else if ($words < 1000000000) {
            // less than a billion
            $words = $words / 1000000 . 'M';
        } else {
            // at least a billion
            $words = $words / 1000000000 . 'B';
        }

        return $words;
    }
}

if (!function_exists('formatPrice')) {
    //formats price - truncate price to 1M, 2K if activated by admin 
    function formatPrice($price, $truncate = false, $forceTruncate = false, $addSymbol = true)
    {
        // convert amount equal to local currency
        if (Session::has('currency_code') && Session::has('local_currency_rate')) {
            $price = floatval($price) / (floatval(env('DEFAULT_CURRENCY_RATE')) || 1);
            $price = floatval($price) * floatval(Session::get('local_currency_rate'));
        }

        // truncate price
        if ($truncate) {
            if (getSetting('truncate_price') == 1 || $forceTruncate == true) {
                if ($price < 1000000) {
                    // less than a million
                    $price = number_format($price, getSetting('no_of_decimals'));
                } else if ($price < 1000000000) {
                    // less than a billion
                    $price = number_format($price / 1000000, getSetting('no_of_decimals')) . 'M';
                } else {
                    // at least a billion
                    $price = number_format($price / 1000000000, getSetting('no_of_decimals')) . 'B';
                }
            }
        } else {
            // decimals
            if (getSetting('no_of_decimals') > 0) {
                $price = number_format($price, getSetting('no_of_decimals'));
            } else {
                $price = number_format($price, getSetting('no_of_decimals'), '.', ',');
            }
        }

        if ($addSymbol) {
            // currency symbol
            $symbol             = Session::has('currency_symbol')           ? Session::get('currency_symbol')           : env('DEFAULT_CURRENCY_SYMBOL');
            $symbolAlignment    = Session::has('currency_symbol_alignment') ? Session::get('currency_symbol_alignment') : env('DEFAULT_CURRENCY_SYMBOL_ALIGNMENT');

            if ($symbolAlignment == 0) {
                return $symbol . $price;
            } else if ($symbolAlignment == 1) {
                return $price . $symbol;
            } else if ($symbolAlignment == 2) {
                # space
                return $symbol . ' ' . $price;
            } else {
                # space
                return $price . ' ' .  $symbol;
            }
        }

        return $price;
    }
}


if (!function_exists('priceToUsd')) {
    // price to usd
    function priceToUsd($price)
    {
        // convert amount equal to local currency
        if (Session::has('currency_code') && Session::has('local_currency_rate')) {
            $price = floatval($price) / floatval(Session::get('local_currency_rate'));
        }

        return $price;
    }
}

if (!function_exists('getProjectIcon')) {
    // getProjectIcon
    function getProjectIcon($type)
    {
        $icon = '';
        switch ($type) {
            case 'image':
                $icon = "image";
                break;
            case 'code':
                $icon = "code";
                break;
            case 'speech':
                $icon = "mic";
                break;
            default:
                $icon = "file-text";
                break;
        }
        return $icon;
    }
}

if (!function_exists('getUsedWordsPercentage')) {
    // getUsedWordsPercentage
    function getUsedWordsPercentage()
    {
        $user = auth()->user();
        $total = $user->this_month_used_words + $user->this_month_available_words;
        if ($total == 0) {
            $total = 1;
        }
        $usedPercent = (100 * $user->this_month_used_words) / $total;
        return $usedPercent > 100 ? 100 : round($usedPercent);
    }
}

if (!function_exists('getUsedImagesPercentage')) {
    // getUsedImagesPercentage
    function getUsedImagesPercentage()
    {
        $user = auth()->user();
        $total = $user->this_month_used_images + $user->this_month_available_images;
        if ($total == 0) {
            $total = 1;
        }
        $usedPercent = (100 * $user->this_month_used_images) / $total;
        return $usedPercent > 100 ? 100 : round($usedPercent);
    }
}

if (!function_exists('getUsedS2TPercentage')) {
    // getUsedS2TPercentage
    function getUsedS2TPercentage()
    {
        $user = auth()->user();
        $total = $user->this_month_available_s2t + $user->this_month_used_s2t;
        if ($total == 0) {
            $total = 1;
        }
        $usedPercent = (100 * $user->this_month_used_s2t) / $total;
        return $usedPercent > 100 ? 100 : round($usedPercent);
    }
}
