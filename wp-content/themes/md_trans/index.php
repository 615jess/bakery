<?php
/**
 * @package WordPress
 * @subpackage WP-Skeleton
 */
 
    get_header();  //the Header
        
    get_template_part( 'menu', 'index' ); //the  menu + logo/site title 
    
    get_template_part( 'loop', 'index' ); //the Loop
            
    get_footer(); //the Footer 
   
?>                        
           
