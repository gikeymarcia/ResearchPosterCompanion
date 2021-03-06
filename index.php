<?php
    require 'settings.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/global.css" rel="stylesheet" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlspecialchars($title); ?></title>
    </head>
    
    <body>
        <div class="leftPanel">
            <img src="<?php echo $posterImgName; ?>" class="fullW"/>    
        </div>
        
        <div class="rightPanel">
            <a href="<?php echo $posterFile; ?>" class="pLink">Download the poster</a>
            <?php
                #### Output links to demo experiments if they're set ####
                if(count($demoExpFolders) > 0) {
                    $i = 1;
                    foreach ($demoExpFolders as $exp) {
                        echo '<a href="'.$exp.'" class="pLink">    Run Experiment '.$i.'</a>';
                        $i++;
                    }
                }
                
                #### Pluralize 'Contact the Author'?
                $count = count($contactAuthors);                // how many people am I giving info for?
                $plural = '';                                   // don't add an 's' to 'Contact the Author'
                if ($count > 1) { $plural = 's'; }              // unless there is more than 1 author
            ?>
            
            <span class="contact">Contact the author<?php echo $plural ?>:</span>
            <?php
                
                $parts = NULL;
                foreach ($contactAuthors as $author) {
                    $parts = explode('::', $author);
                    echo '<a href="mailto:'.$parts[1].'" class="email">'.$parts[0].'</a>';
                }
            if($creditCollector == TRUE) {
                ?>
                <p>Experiments created with <b>Collector</b> (a free and open-source solution designed for running psychology experiments on the web)
                <br /><br /> Get it <a href="https://github.com/gikeymarcia/Collector/tree/dev" target="_blank">here.</a>            
                </p>
                <?php
            }
            ?>
        </div>
    </body>
</html>