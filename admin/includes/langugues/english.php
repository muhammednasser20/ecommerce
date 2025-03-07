<?php

    function lang($phrase) {

        static $lang = array(

            // dashbaord pages
            //navbar text
            'profile user editing' => 'Edit Profile',
            'profile user Settings' => 'Settings' , 
            'profile user logout' => 'Log Out' ,

            //end navbar

            //side bar text
            'side menu home' => 'Home',
            'side menu Categories' => 'Categories',
            'side menu Items' => 'Items',
            'side menu Members' => 'Members',
            'side menu Statics' => 'Statics',
            'side menu file' => 'Logs',
            //end side bar text

            //start tab top
            'Defult' => 'Admin panel',

        );
            return $lang[$phrase];


    }