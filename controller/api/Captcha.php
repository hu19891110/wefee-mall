<?php namespace addons\wefeemall\controller\api;

use think\Controller;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;

class Captcha extends Controller
{

    public function index()
    {
        $phraseBuilder = new PhraseBuilder(4, '0123456789');
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        session('verify_code', $builder->getPhrase());
        // Output
        ob_clean();
        header('Content-type: image/jpeg');
        $builder->output();
    }

}