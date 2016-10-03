<h1> This is it ! </h1>

<?php

if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); 
		
			templator_to_pdf(get_the_content());
		
					
			//
			// Post Content here
			//
		} // end while
	} // end if

	exit;
