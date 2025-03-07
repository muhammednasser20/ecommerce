<?php

    function lang($phrase) {

        static $lang = array(

            'Message' => 'welcome in arbic',
            'ADMIN' => 'arabic admin'

        );
            return $lang[$phrase];


    }