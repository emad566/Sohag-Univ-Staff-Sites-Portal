<?php

function setLang(){
    if(Auth::check())
    {
        session()->put('lang', Auth()->user()->lang);
    }else{
        $lang = (@$_GET['lang']) ? @$_GET['lang'] : "";
        if($lang != "")
        {
            session()->put('lang', $lang);
        }elseif(!session()->has('lang'))
        {
            session()->put('lang', 'ar');
        }
    }
    
    App()->setLocale(session()->get('lang'));
}

