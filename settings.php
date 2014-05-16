<?php
    $title          = 'Garcia, Yan, Yu, Bjork, Bjork (2012) Psychonomics';                  // Page title
    $posterImgName  = 'poster.jpg';                                                         // points towards the image you made of the poster
    $posterFile     = 'Garcia, Yan, Yu, Bjork, Bjork (2012) Psychonomics Poster.pdf';       // Filename (case sensitive) of the downloadable poster
    $contactAuthors = array(                                                                // format Authors as 'Name::email@email.com'
                            'Michael Garcia::gikeymarcia@ucla.edu',
                            'Veronica Yan::veronicayan@ucla.edu',
                            );
    $demoExpFolders = array(                                                                // delete contents of array(); to turn off links to demo experiments
                            'Exp/',
                            'Exp2/',
                            );
    $creditCollector = TRUE;                // if you made the experiment with collector you can set this to 'TRUE' to spread the love
    
    /*
     * Notes: When you make the image of your poster a width of ~1700px seems to work well.
     *        The page will resize most images to fit
     */
?>